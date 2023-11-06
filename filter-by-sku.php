<?php
include "api/communication.php";
include 'system/init.php';
include "include/header.php";

$dbRpro = new rpro\CustomConnection;

$helper = new common\Helper;
$objectRproData = new stdClass();
 
if($_POST)
{
    $helper->haveFilters = true;
    $helper->sku = $_POST["sku"];
}
else
{
    if($_GET)
    {
        $helper->haveFilters = true;
        $helper->sku = $_POST["sku"];
    }
    else
    {
        $helper->haveFilters = false;
        $helper->sku = "";
    }
}
 
 
$dbRpro->dbConnection();

if($helper->sku != "")
{
    $sql = "select nombre, marca, categoria, listado_tiendas from producto_tiendas_disponibilidad where codigo_producto = '{$helper->sku}'";
    $dbRpro->dbQuery($sql);
    $list = $dbRpro->dbGetResult();

    $stores = $list[0]["listado_tiendas"];
    $arr_stores = explode(",", $stores);
    sort($arr_stores);
}
?>
                <div class="app-main__outer">
                    <div class="app-main__inner">
                        <div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-home icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Dashboard mix tiendas
                                        <div class="page-title-subheading">Busqueda de productos.
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


                                        
                                        <form name="form_hourly_event" id="form_hourly_event" method="post" action="filter-by-sku.php">
                                            <ul class="tabs-animated-shadow tabs-animated nav">
                                                <li class="nav-item">
                                                    <!-- Puede filtrar su busqueda por:  -->
                                                    <a role="tab" class="nav-link active show" id="tab-c-0" data-toggle="tab" href="#tab-animated-0" aria-selected="true">
                                                        <span>SKU</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a role="tab" class="nav-link" id="tab-c-1" data-toggle="tab" href="#tab-animated-1">
                                                        <span>Nombre/Descripción</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active show" id="tab-animated-0" role="tabpanel">
                                                    <div class="form-group row">
                                                        <label for="datepicker" class="col-sm-2 col-form-label">Ingrese el SKU</label>
                                                        <div class="col-sm-6">
                                                            <input placeholder="" type="text" class="mb-2 form-control" name="sku" value="<?php echo $helper->sku; ?>">
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <button type="submit" class="btn btn-primary btn-send-data">Consultar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="tab-animated-1" role="tabpanel">
                                                    <p class="mb-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                                        unknown
                                                        printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. </p>
                                                </div>
                                            </div>
                                        
                                        
                                        
                                        
                                        

                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="mb-3 progress">
                            <div class="progress-bar progress-bar-animated bg-info progress-bar-striped" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                        </div> -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="main-card mb-3 card">

                                    <div class="card-header">
                                        Detalles del sku.
                                    </div>
                                    <div class="card-body table-responsive">
                                        <?php
                                        if($helper->haveFilters)
                                        {
                                            ?>
                                            <div class="row">
                                                <div class="col-lg-6 col-xl-4">
                                                    <div class="card mb-3 widget-content">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left">
                                                                <div class="widget-heading">Producto</div>
                                                                <div class="widget-subheading"><?php echo ucwords(strtolower($list[0]["nombre"])); ?></div>
                                                            </div>
                                                            <div class="widget-content-right">
                                                                <div class="widget-numbers text-success">
                                                                    <i class="fa fa-fw fa-barcode" aria-hidden="true" title="Producto"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-xl-4">
                                                    <div class="card mb-3 widget-content">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left">
                                                                <div class="widget-heading">Marca</div>
                                                                <div class="widget-subheading"><?php echo ucwords(strtolower($list[0]["marca"])); ?></div>
                                                            </div>
                                                            <div class="widget-content-right">
                                                                <div class="widget-numbers text-success">
                                                                    <i class="fa fa-fw fa-copyright" aria-hidden="true" title="Marca"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-xl-4">
                                                    <div class="card mb-3 widget-content">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left">
                                                                <div class="widget-heading">Categoría</div>
                                                                <div class="widget-subheading"><?php echo ucwords(strtolower($list[0]["categoria"])); ?></div>
                                                            </div>
                                                            <div class="widget-content-right">
                                                                <div class="widget-numbers text-success">
                                                                    <i class="fa fa-fw fa-align-justify" aria-hidden="true" title="Categoría"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            echo "Disponible en:<br>";
                                            echo "<div class='animated animate__slideInLeft animate__delay-4s'>";
                                            foreach($arr_stores as $stores)
                                            {
                                                $sqlStore = "SELECT nombre from tienda where codigo_tienda = '{$stores}'";
                                                $dbRpro->dbQuery($sqlStore);
                                                $store_name = $dbRpro->dbGetResult();

                                                echo utf8_encode($store_name[0]["nombre"]) . "<br>";
                                            }
                                            echo "</div>";
                                        }
                                        else
                                        {
                                            ?>
                                            <p class="text-muted">
                                                Para comenzar, ingrese el SKU a consultar.
                                            </p>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="d-block text-center card-footer">
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
$dbRpro->dbClose();
include "include/footer.php";
?>
