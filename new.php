<?php
include "include/header.php";
$helper = new common\Helper;
$showMessage = false;
$showEditTitle = false;
$msg_type = "success";
$sku_visibility = "";
$visibility = "display: none;";
if($_GET)
{
    $alu = $_GET["alu"];
    $canal = $_GET["canal"];

    $result = $sds->search($alu, $canal);
    if(SUMAR){
        $result[0]["qty"] = isset($result[0]["qty"]) ? $result[0]["qty"] - 1 : "";
        $result[0]["seguridad"] = isset($result[0]["seguridad"]) ? $result[0]["seguridad"] - 1 : "";
    }
    $sku_visibility = "display: none;";
    $showEditTitle = true;
}
elseif($_POST)
{
    $sbs_no = $_POST["sbs_no"];
    $store_no = $_POST["store_no"];
    $alu = $_POST["alu"];
    $qty = $_POST["qty"];
    $seguridad = $_POST["seguridad"];
    $canal = $_POST["canal"];
    $desde = $_POST["desde"];
    $hasta = $_POST["hasta"];

    $desde = $desde != "" ? $helper->formatDate($desde) : null;
    $hasta = $hasta != "" ? $helper->formatDate($hasta) : null;

    $validateDate = $helper->validateDate($desde, $hasta);
    if($validateDate){
        if(SUMAR){
            $qty = $qty + 1;
            $seguridad = $seguridad + 1;
        }
        if(($sbs_no != "")&&($store_no != "")&&($alu != "")&&($qty != "")&&($seguridad != "")&&($canal != ""))
        {
            $fvalues = array(
                "sbs_no" => $sbs_no,
                "store_no" => $store_no,
                "alu" => strtoupper(trim($alu)),
                "qty" => $qty,
                "seguridad" => $seguridad,
                "canal" => $canal,
                "desde" => "TO_DATE('$desde', 'YYYY-MM-DD')",
                "hasta" => "TO_DATE('$hasta', 'YYYY-MM-DD')"
            );
            $result = $sds->insert($fvalues);

            if($result == "OK")
            {
                $msg = "Registro agregado correctamente.";
                $showMessage = true;
                $msg_type = "success";
            }
            elseif($result == "NOK_DELETE")
            {
                $msg = "Ocurrio un error al elimiar el registro anterior, contactese con el area de desarrollo (Error #1002).";
                $showMessage = true;
                $msg_type = "danger";
            }
            elseif($result == "NOK_ERROR_INSERT")
            {
                $msg = "Ocurrio un error al agregar el registro, contactese con el area de desarrollo (Error #1001).";
                $showMessage = true;
                $msg_type = "danger";
            }
            elseif($result == "NOK_ALREADY_EXIST")
            {
                $msg = "No se puede agregar el registro, ya existe este SKU para este canal de venta.";
                $showMessage = true;
                $msg_type = "danger";
            }
            else
            {
                $msg = "Ocurrio un error al agregar el registro.";
                $showMessage = true;
                $msg_type = "danger";
            }
        }
        else
        {
            $msg = "Debe completar los campos obligatorios.";
            $showMessage = true;
            $msg_type = "danger";
        }
    }else{
        $msg = "La fecha desde no puede ser mayor a la fecha hasta.";
        $showMessage = true;
        $msg_type = "danger";
    }
}
?>
                <div class="app-main__outer">
                    <div class="app-main__inner">
                        <div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-plus icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Administrador de stock de seguridad e iluminación
                                        <div class="page-title-subheading">Agrega un nuevo registro al sistema para sincronizar.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>   

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="main-card mb-3 card">
                                        <?php
                                        if($showEditTitle):
                                        ?>
                                            <div class="card-header">
                                                Editar SKU: <?php echo "{$result[0]["alu"]}"; ?>
                                            </div>
                                        <?php
                                        else:
                                        ?>
                                        <div class="card-header">
                                            Agregar nuevo SKU
                                        </div>
                                        <?php
                                        endif;
                                        ?>

                                        <?php
                                        if($showMessage):
                                        ?>
                                            <li class="list-group-item-<?php echo $msg_type; ?> list-group-item"><?php echo $msg; ?></li>
                                        <?php
                                        endif;
                                        ?>

                                        <div class="card-body">
                                            <form name="form_new" id="form_new" method="post" action="new.php">
                                                <div class="row">
                                                    <div class="col-sm-2" style="<?php echo "{$visibility}"; ?>">
                                                        <input type="text" class="mb-2 form-control" name="sbs_no" id="" value="<?php echo (isset($result[0]["sbs_no"]))?$result[0]["sbs_no"]:"2"; ?>" placeholder="SBS_NO" required="" autocorrect="off" autocapitalize="none" autocomplete="off">
                                                    </div>
                                                    <div class="col-sm-2" style="<?php echo "{$visibility}"; ?>">
                                                        <input type="text" class="mb-2 form-control" name="store_no" id="" value="<?php echo (isset($result[0]["store_no"]))?$result[0]["store_no"]:"48"; ?>" placeholder="STORE_NO" required="" autocorrect="off" autocapitalize="none" autocomplete="off">
                                                    </div>
                                                    <div class="col-sm-4" style="<?php echo "{$sku_visibility}"; ?>">
                                                        <input type="text" class="mb-2 form-control" name="alu" id="" value="<?php echo (isset($result[0]["alu"]))?$result[0]["alu"]:""; ?>" placeholder="SKU" required="" autocorrect="off" autocapitalize="none" autocomplete="off">
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="mb-2 form-control" name="qty" id="" value="<?php echo (isset($result[0]["qty"]))?$result[0]["qty"]:""; ?>" placeholder="N° ILUMINADO" required="" autocorrect="off" autocapitalize="none" autocomplete="off">
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="mb-2 form-control" name="seguridad" id="" value="<?php echo (isset($result[0]["seguridad"]))?$result[0]["seguridad"]:""; ?>" placeholder="N° SDS" required="" autocorrect="off" autocapitalize="none" autocomplete="off">
                                                    </div>
                                                    <?php
                                                    if(isset($result[0]["canal"])):
                                                    ?>
                                                    <div class="col-sm-4">
                                                        <select class="mb-2 form-control" name="canal" required="">
                                                            <option value="">Seleccione canal</option>
                                                            <option value="MAGENTO" <?php echo ($result[0]["canal"] == "MAGENTO")?"selected":""; ?>>Magento</option>
                                                            <option value="MULTIVENDE" <?php echo ($result[0]["canal"] == "MULTIVENDE")?"selected":""; ?>>Multivende</option>
                                                        </select>
                                                    </div>
                                                    <?php
                                                    else:
                                                    ?>
                                                    <div class="col-sm-4">
                                                        <select class="mb-2 form-control" name="canal" required="">
                                                            <option value="">Seleccione canal</option>
                                                            <option value="MAGENTO">Magento</option>
                                                            <option value="MULTIVENDE">Multivende</option>
                                                        </select>
                                                    </div>
                                                    <?php
                                                    endif;
                                                    ?>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="mb-2 form-control datepicker" name="desde" id="datepicker" value="<?php echo (isset($result[0]["desde"]))?$result[0]["desde"]:""; ?>" placeholder="Fecha desde" autocorrect="off" autocapitalize="none" autocomplete="off">
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="mb-2 form-control datepicker" name="hasta" id="datepicker" value="<?php echo (isset($result[0]["hasta"]))?$result[0]["hasta"]:""; ?>" placeholder="Fecha hasta" autocorrect="off" autocapitalize="none" autocomplete="off">
                                                    </div>

                                                    <div class="col-sm-12">
                                                        <button type="submit" class="btn btn-primary btn-new">Agregar</button>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="app-wrapper-footer">
                        <div class="app-footer">
                            <div class="app-footer__inner">
                                <div class="app-footer-center" style="text-align: center; width: 100%;">
                                    Desarrollado por la Gerencia de Tecnología DBS
                                </div>
                            </div>
                        </div>
                    </div>   
                </div>
<?php
include "include/footer.php";
?>
