<?php
include "include/header.php";

$host_prefix_default = $helper->get_config("host_prefix_default");
$helper->host = $helper->get_config("{$host_prefix_default}host");
$helper->port = $helper->get_config("{$host_prefix_default}port");
$helper->username = $helper->get_config("{$host_prefix_default}username");
$helper->password = $helper->get_config("{$host_prefix_default}password");

$crontab = new crontab\Ssh2_crontab_manager($helper->host, $helper->port, $helper->username, $helper->password);
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
                                    <div>Administrador de stock de seguridad e iluminación
                                        <div class="page-title-subheading">Panel para administrar el stock de seguridad de los sku, tanto para el canal de Magento y Multivende por separado, además desde acá se puede cargar el stock iluminado a estos canales de venta.
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
                                        
                                        <form name="form_hourly_event" id="form_hourly_event" method="post" action="home.php">
                                            <!-- <div class="form-group row">
                                                <label for="datepicker" class="col-sm-2 col-form-label">Empresa</label>
                                                <div class="col-sm-6">
                                                <select name="empresa" class="empresa form-control form-control">
                                                    <option value="">Seleccione...</option>
                                                    <option value="DBS">DBS</option>
                                                    <option value="MU">Make Up</option>
                                                    <option value="PM">Prismology</option>
                                                </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    <button type="submit" class="btn btn-primary btn-send-data">Consultar</button>
                                                </div>
                                            </div> -->
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="mb-3 progress">
                            <div class="progress-bar progress-bar-animated bg-info progress-bar-striped" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                        </div> -->

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
