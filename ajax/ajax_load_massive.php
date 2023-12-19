<?php
include '../system/init.php';
$helper = new common\Helper;
$sds = new process_ilu\sds;
$sds->dbWeb1 = new database\Web1;
$sds->dbWeb1->dbConnection();

if($_POST){
    //se verifica si existe el archivo
    if (isset($_FILES['archivo_excel'])) {
        require_once '../inc/PHPExcel/Classes/PHPExcel.php';
        $archivo = $_FILES['archivo_excel']['tmp_name'];
        $inputFileType = PHPExcel_IOFactory::identify($archivo);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($archivo);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $resultData = array();

        //validamos los encabezados del archivo para saber que sea el correcto
        $skuEnc = $sheet->getCell("A1")->getValue();
        $iluEnc = $sheet->getCell("B1")->getValue();
        $sdsEnc = $sheet->getCell("C1")->getValue();
        $canalEnc = $sheet->getCell("D1")->getValue();
        $desdeEnc = $sheet->getCell("E1")->getValue();
        $hastaEnc = $sheet->getCell("F1")->getValue();

        if ($skuEnc != "SKU" || $iluEnc != "ILUMINADO" || $sdsEnc != "SDS" || $canalEnc != "CANAL" || $desdeEnc != "FECHA_DESDE" || $hastaEnc != "FECHA_HASTA") {
            $resultData[] = array(
                "alu" => '',
                "canal" => '',
                "msg" => "El archivo no es el correcto, favor revisar.",
                "msg_type" => "danger"
            );
            echo json_encode($resultData);
            die();
        }

        //recorremos todas las filas para insertar los datos
        for($i = 2; $i <= $highestRow; $i++){
            $sbs_no = "2";
            $store_no = "48";
            $alu = $sheet->getCell("A".$i)->getValue();
            $qty = $sheet->getCell("B".$i)->getValue();
            $seguridad = $sheet->getCell("C".$i)->getValue();
            $canal = $sheet->getCell("D".$i)->getValue();
            $desde = $sheet->getCell("E".$i)->getValue();
            $hasta = $sheet->getCell("F".$i)->getValue();
            // Convertir el número serial de fecha a formato de fecha en PHP
            $desde = $desde != false ? \PHPExcel_Shared_Date::ExcelToPHP($desde) : false;
            $hasta = $hasta != false ? \PHPExcel_Shared_Date::ExcelToPHP($hasta) : false;

            // Formatear las fechas según sea necesario
            // $desde = $desde != false ? date('d/m/Y',  strtotime('+1 day', $desde)) : false;
            $desde = $desde != false ? date('d/m/Y',  strtotime('+0 day', $desde)) : false;
            $hasta = $hasta != false ? date('d/m/Y',  strtotime('+0 day', $hasta    )) : false;
             //validar qty y seguridad para que no sean menores a 0 y sea un valor numerico valido
            if($qty < 0 || $qty == "" || $qty == null || !is_numeric($qty)){
                $qty = 0;
            }

            if($seguridad < 0 || $seguridad == "" || $seguridad == null || !is_numeric($seguridad)){
                $seguridad = 0;
            }

            if(SUMAR){
                $qty = $qty + 1;
                $seguridad = $seguridad + 1;
            }

            //validamos que no se ingresen datos vacíos
            if(($sbs_no != "")&&($store_no != "")&&($alu != "")&&($qty != "")&&($seguridad != "")&&($canal != "")){
                //se verifica si es multicanal
                $canal = explode("-",$canal);
                $desde = $desde != false ? $helper->formatDate($desde) : null;
                $hasta = $hasta != false ? $helper->formatDate($hasta) : null;
                $validateDate = $helper->validateDate($desde, $hasta);
                if($validateDate){
                    for($j = 0; $j < count($canal); $j++){
                        //transformamos el canal en mayuscula y eliminamos espacios
                        $canal[$j] = strtoupper(trim($canal[$j]));
                        //validamos los canales permitidos
                        if($canal[$j] == 'MAGENTO' || $canal[$j] == 'MULTIVENDE'){
                            $fvalues = array(
                                "sbs_no" => $sbs_no,
                                "store_no" => $store_no,
                                "alu" => strtoupper(trim($alu)),
                                "qty" => $qty,
                                "seguridad" => $seguridad,
                                "canal" => $canal[$j],
                                "desde" => "TO_DATE('$desde', 'YYYY-MM-DD')",
                                "hasta" => "TO_DATE('$hasta', 'YYYY-MM-DD')"
                            );
                            // die();
                            $result = $sds->insert($fvalues);
                            if($result == "OK"){
                                $msg = "Registro agregado correctamente.";
                                $msg_type = "success";
                                $nro_registro = $i;

                            }elseif($result == "NOK_DELETE"){
                                $msg = "Ocurrio un error al elimiar el registro anterior, contactese con el area de desarrollo (Error #1002).";
                                $msg_type = "danger";
                                $nro_registro = $i;

                            }elseif($result == "NOK_ERROR_INSERT"){
                                $msg = "Ocurrio un error al agregar el registro, contactese con el area de desarrollo (Error #1001).";
                                $msg_type = "danger";
                                $nro_registro = $i;

                            }elseif($result == "NOK_ALREADY_EXIST"){
                                $msg = "No se puede agregar el registro, ya existe este SKU para este canal de venta.";
                                $msg_type = "danger";
                                $nro_registro = $i;

                            }else{
                                $msg = "Ocurrio un error al agregar el registro.";
                                $msg_type = "danger";
                                $nro_registro = $i;
                            }

                            $resultData[] = array(
                                "nro_registro" => $nro_registro,
                                "alu" => $alu,
                                "canal" => $canal[$j],
                                "msg" => $msg,
                                "msg_type" => $msg_type,
                            );
                        }else{
                            $resultData[] = array(
                                "nro_registro" => $i,
                                "alu" => $alu,
                                "canal" => $canal[$j],
                                "msg" => "El canal no es válido.",
                                "msg_type" => "danger",
                            );
                        }
                    }
                }else{
                    $resultData[] = array(
                        "nro_registro" => $i,
                        "alu" => $alu,
                        "canal" => $canal,
                        "msg" => "La fecha desde no puede ser mayor a la fecha hasta.",
                        "msg_type" => "danger",
                    );
                }
            }else{
                $resultData[] = array(
                    "nro_registro" => $i,
                    "alu" => $alu != null ? $alu : '',
                    "canal" => $canal != null ? strtoupper($canal) : '',
                    "msg" => "No se pudo agregar el registro, existen campos vacíos.",
                    "msg_type" => "danger",
                );
            }
        }
        echo json_encode($resultData);
    }
}