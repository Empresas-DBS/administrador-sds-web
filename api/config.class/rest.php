<?php
namespace dbsresources;

class Communication
{
    public $apptoken;
    public $devtoken;
    public $inactivity;

    public function __construct($app_token, $dev_token)
    {
		$this->apptoken = $app_token;
		$this->devtoken = $dev_token;
    }

    private function getData($method, $request)
    {
        $fields_string = http_build_query($request);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, _API_URL_ . $method);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        
        $response = curl_exec($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);
        if(!$response) 
        {
            echo "Error de comunicación";
        }
        else
        {
            return $response;
        }
    }

    public function getLoginButton()
    {
        $request = [
            "apptoken" => $this->apptoken,
            "devtoken" => $this->devtoken
        ];

        $resp = $this->getData("get-button", $request);
        $json = json_decode($resp);

        if(isset($json->status))
        {
            switch($json->status)
            {
                case("400"):
                    return $json->messages->error;
                    break;
                case("401"):
                    return $json->messages->error;
                    break;
            }
        }
        else
        {
            return $resp;
        }
    }

    public function getUserData($mail, $name)
    {
        $request = [
            "apptoken" => $this->apptoken,
            "devtoken" => $this->devtoken,
            "mail" => $mail,
            "name" => $name
        ];
        $resp = $this->getData("get-userdata", $request);
        $json = json_decode($resp);

        return $json;
    }

    public function updateLog($used, $status, $token_url)
    {
        $request = [
            "apptoken" => $this->apptoken,
            "devtoken" => $this->devtoken,
            "used" => $used,
            "status" => $status,
            "token_url" => $token_url
        ];
        $resp = $this->getData("update-log", $request);
        $json = json_decode($resp);

        return $resp;
    }

    public function createSession($mail)
    {
        // $length = 32;
        // $token_sess = bin2hex(random_bytes(($length - ($length % 2)) / 2));
        $tmp_token_sess = base64_encode(date("H:i:s"));
        $token_sess = substr($tmp_token_sess, 0, 32);

        $request = [
            "apptoken" => $this->apptoken,
            "devtoken" => $this->devtoken,
            "mail" => $mail,
            "id_session" => $token_sess
        ];
        $resp = $this->getData("register-session", $request);
        $json = json_decode($resp);

        return $resp;
    }

    public function getPermissionsFromSession($sessmail, $sessid)
    {
        $request = [
            "apptoken" => $this->apptoken,
            "devtoken" => $this->devtoken,
            "mail" => $sessmail,
            "id_session" => $sessid
        ];

        $resp = $this->getData("get-session-perms", $request);
        $json = json_decode($resp);

        if(isset($json->status))
        {
            switch($json->status)
            {
                case("400"):
                    return $json->messages->error;
                    break;
                case("401"):
                    return $json->messages->error;
                    break;
            }
        }
        else
        {
            return $json;
        }
    }

    public function getUserDataFromSession($id_session)
    {
        $request = [
            "apptoken" => $this->apptoken,
            "devtoken" => $this->devtoken,
            "id_session" => $id_session
        ];
        $resp = $this->getData("get-session-userdata", $request);
        $json = json_decode($resp);

        if(isset($json->status))
        {
            switch($json->status)
            {
                case("400"):
                    return $json->messages->error;
                    break;
                case("401"):
                    $json->messages->error;
                    break;
            }
        }
        else
        {
            return $json;
        }
    }

    public function destroySessionButton($sessmail, $sessid)
    {
        $request = [
            "apptoken" => $this->apptoken,
            "mail" => $sessmail,
            "id_session" => $sessid
        ];
        $resp = $this->getData("get-destroy-button", $request);
        $json = json_decode($resp);

        if(isset($json->status))
        {
            switch($json->status)
            {
                case("400"):
                    return $json->messages->error;
                    break;
                case("401"):
                    return $json->messages->error;
                    break;
            }
        }
        else
        {
            return $resp;
        }
    }
}

