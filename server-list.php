<?php
include "include/header.php";

$host_prefix_default = $helper->get_config("host_prefix_default");
$server->get_list();
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
                                        <div class="page-title-subheading">Listado de servidores configurados.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>   


                        <div class="row">
                            <div class="col-md-12">
                                <div class="main-card mb-3 card">

                                    <div class="card-header">
                                        Servidores.
                                    </div>
                                    <div class="card-body table-responsive">

                                        <table class="mb-0 table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th width="10">Estado</th>
                                                    <th>Alias</th>
                                                    <th>Host</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach($server->all as $index => $data)
                                                {
                                                    $index = $index + 1;
                                                    $prefix = explode("_", $data['llave'])[0] . "_";
                                                    $host = $helper->get_config($prefix . "host");
                                                    $status = $helper->get_config($prefix . "status");
                                                    
                                                    $activate_server_button_text = "Seleccionar";
                                                    $activate_button_class = "btn-outline-info";
                                                    $activate_button_disabled = "";
                                                    $activate_button_data_title = "Seleccionar para configurar como servidor por defecto";
                                                    if($host_prefix_default == $prefix)
                                                    {
                                                        $activate_server_button_text = "Seleccionado";
                                                        $activate_button_class = "btn-outline-success";
                                                        $activate_button_disabled = "disabled";
                                                        $activate_button_data_title = "";
                                                    }

                                                    if($status == "conected")
                                                    {
                                                        $class_bg_circle_indicator = "bg-success";
                                                    }
                                                    elseif($status == "not_conected")
                                                    {
                                                        $class_bg_circle_indicator = "bg-danger";
                                                    }
                                                    ?>
                                                    <tr>
                                                        <th scope="row"><?php echo "{$index}"; ?></th>
                                                        <td>
                                                            <div class="swatch-holder <?php echo $class_bg_circle_indicator; ?>"></div>
                                                        </td>
                                                        <td><?php echo "{$data['valor']}"; ?></td>
                                                        <td><?php echo $host; ?></td>
                                                        <td>
                                                            <button class="mb-2 mr-2 btn-transition btn btn-outline-primary btn-edit" data-prefix="<?php echo $prefix; ?>">
                                                                Editar
                                                            </button>
                                                            <button class="mb-2 mr-2 btn-transition btn <?php echo "{$activate_button_class}"; ?> btn-set-default" data-prefix="<?php echo $prefix; ?>" <?php echo $activate_button_disabled; ?> data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $activate_button_data_title; ?>">
                                                                <?php echo "{$activate_server_button_text}"; ?>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>

                                        <form id="form_edit" action="server-edit.php" method="post">
                                            <input type="hidden" name="hidden_prefix" id="hidden_prefix">
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
