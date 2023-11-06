<?php
include "include/header.php";

// $helper->host = $helper->get_config('host');
// $helper->port = $helper->get_config('port');
// $helper->username = $helper->get_config('username');
// $helper->password = $helper->get_config('password');

// $crontab = new crontab\Ssh2_crontab_manager($helper->host, $helper->port, $helper->username, $helper->password);
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
                                    <div>Servidores
                                        <div class="page-title-subheading">Administra los servidores a los que conectarse.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>   


                        <div class="row">
                            <div class="col-md-12">
                                <div class="main-card mb-3 card">

                                    <div class="card-header">
                                        Nuevo servidor.
                                    </div>
                                    <div class="card-body table-responsive">
                                        <form name="cron_new" id="cron_new" method="post" action="server-add-process.php">
                                            <div class="row">
                                                <div class="col-sm-10">
                                                    <input type="text" class="mb-2 form-control" name="serverAlias" id="serverAlias" value="" placeholder="Alias" required="" autocorrect="off" autocapitalize="none">
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="text" class="mb-2 form-control" name="serverPort" id="serverPort" value="22" placeholder="22" required="" autocorrect="off" autocapitalize="none">
                                                </div>

                                                <div class="col-sm-4">
                                                    <input type="text" class="mb-2 form-control" name="serverHost" id="serverHost" value="" placeholder="Host" required="" autocorrect="off" autocapitalize="none">
                                                </div>
                                                <div class="col-sm-4">
                                                    <input type="text" class="mb-2 form-control" name="serverUser" id="serverUser" value="" placeholder="User" required="" autocorrect="off" autocapitalize="none">
                                                </div>
                                                <div class="col-sm-4">
                                                    <input type="text" class="mb-2 form-control" name="serverPassword" id="serverPassword" value="" placeholder="Password" required="" autocorrect="off" autocapitalize="none">
                                                </div>

                                                <div class="col-sm-12">
                                                    <button type="submit" class="btn btn-primary btn-new-server">Agregar</button>
                                                </div>
                                            </div>
                                        </form>
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
include "include/footer.php";
?>
