<?php
error_reporting(0);
ini_set('max_execution_time', 15000);
set_time_limit(0);
ini_set('memory_limit', '-1');
include "include/header.php";
$showResult = true;
if($_POST)
{
    $search_sku = strtoupper(trim($_POST["search_sku"]));
    $search_canal = $_POST["search_canal"] != "-1" ? trim($_POST["search_canal"]) : "";
    $found = $sds->search($search_sku, $search_canal);

    $showResult = true;
}
?>
                <input type="hidden" id="sumar" value="<?php echo SUMAR ?>">
                <div class="app-main__outer">
                    <div class="app-main__inner">
                        <div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-search icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Administrador de stock de seguridad e iluminación
                                        <div class="page-title-subheading">Busca por SKU, edita o elimina datos.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <!-- Titulo -->
                                        </h5>

                                        <form name="form_hourly_event" id="form_hourly_event" method="post" action="search.php" onsubmit="return false;">
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">SKU:</label>
                                                <div class="col-sm-4">
                                                    <input name="search_sku" id="sku" placeholder="" type="text" class="form-control" autocomplete="off" value="<?php echo $showResult && isset($search_sku) ? $search_sku : '' ?>">
                                                </div>
                                                <div class="col-sm-3">
                                                    <select name="search_canal" id="search_canal" class="form-control">
                                                        <option value="-1" selected>Todos los canales</option>
                                                        <option value="MAGENTO">Magento</option>
                                                        <option value="MULTIVENDE">Multivende</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-3">
                                                    <button class="btn btn-primary btn-send-data" id="consultarServerSide">Consultar</button>
                                                </div>
                                                <!-- <div class="col-sm-3">
                                                    <button type="submit" class="btn btn-primary btn-send-data">Consultar</button>
                                                </div> -->
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                        if($showResult):
                        ?>
                        <!-- <div class="mb-3 progress">
                            <div class="progress-bar progress-bar-animated bg-info progress-bar-striped" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                        </div> -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="main-card mb-3 card" id="contentTable">

                                    <div class="card-header">
                                        Resultados &nbsp;
                                        <a href="javascript:void(0)" id="downloadExcel" class="btn btn-primary btn-small" onclick="downloadExcel()" >Excel</a>
                                    </div>
                                    <div class="card-body table-responsive">
                                        <table class='table table-hover table-bordered table-striped text-center' id='table-data-show2' data-page-length='100' data-searching='true' width="100%">
                                            <thead>
                                            <tr>
                                                <th scope='col'>ID</th>
                                                <th scope='col'>SBS_NO</th>
                                                <th scope='col'>STORE_NO</th>
                                                <th scope='col'>SKU</th>
                                                <th scope='col'>ILUMINADO</th>
                                                <th scope='col'>SDS</th>
                                                <th scope='col'>DESDE</th>
                                                <th scope='col'>HASTA</th>
                                                <th scope='col'>CANAL</th>
                                                <th scope='col'>ACCIÓN</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                        <!-- <table class='table table-hover table-bordered table-striped text-center' id='table-data-show1' data-page-length='100'>
                                            <thead>
                                                <tr>
                                                    <th scope='col'>ID</th>
                                                    <th scope='col'>SBS_NO</th>
                                                    <th scope='col'>STORE_NO</th>
                                                    <th scope='col'>SKU</th>
                                                    <th scope='col'>ILUMINADO</th>
                                                    <th scope='col'>SDS</th>
                                                    <th scope='col'>DESDE</th>
                                                    <th scope='col'>HASTA</th>
                                                    <th scope='col'>CANAL</th>
                                                    <th scope='col'>ACCIÓN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php /*
                                                foreach($found as $k => $v)
                                                {
                                                    if(SUMAR){
                                                        $v["qty"] = $v["qty"] - 1;
                                                        $v["seguridad"] = $v["seguridad"] - 1;
                                                    }
                                                    ?>
                                                    <tr class=''>
                                                        <th scope='row'><?php echo substr($v["id"], -5); ?></th>
                                                        <td><?php echo $v["sbs_no"]; ?></td>
                                                        <td><?php echo $v["store_no"]; ?></td>
                                                        <td><?php echo $v["alu"]; ?></td>
                                                        <td><?php echo $v["qty"]; ?></td>
                                                        <td><?php echo $v["seguridad"]; ?></td>
                                                        <td data-order=''><?php echo $v["desde"]; ?></td>
                                                        <td data-order=''><?php echo $v["hasta"]; ?></td>
                                                        <td><?php echo $v["canal"]; ?></td>
                                                        <td>
                                                            <button class="mb-2 mr-2 btn-transition btn btn-outline-primary btn-edit" data-alu="<?php echo "{$v["alu"]}"; ?>" data-canal="<?php echo "{$v["canal"]}"; ?>">
                                                                <i class="fa fa-fw" aria-hidden="true" title=""></i>
                                                            </button>
                                                            <button class="mb-2 mr-2 btn-transition btn btn-outline-danger btn-delete" data-alu="<?php echo "{$v["alu"]}"; ?>" data-canal="<?php echo "{$v["canal"]}"; ?>">
                                                                <i class="fa fa-fw" aria-hidden="true" title=""></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                */ 
                                                ?>
                                        </table> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        endif;
                        ?>
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
