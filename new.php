<?php
include "include/header.php";

$display_text = "display:none;";
if($_POST)
{
    $host_prefix_default = $helper->get_config("host_prefix_default");
    $helper->host = $helper->get_config("{$host_prefix_default}host");
    $helper->port = $helper->get_config("{$host_prefix_default}port");
    $helper->username = $helper->get_config("{$host_prefix_default}username");
    $helper->password = $helper->get_config("{$host_prefix_default}password");

    $crontab = new crontab\Ssh2_crontab_manager($helper->host, $helper->port, $helper->username, $helper->password);

    /**
     * *Anexar un trabajo cron
     */
    $cron_part = $_POST['cronInput'];
    $path_part = $_POST['scriptPath'];
    $cron_line = $cron_part . " " . $path_part;
    $crontab->append_cronjob($cron_line);
    $display_text = "display:block;";
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
                                    <div>Dashboard crontab
                                        <div class="page-title-subheading">Agregar una nueva tarea cron al sistema.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>   

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="main-card mb-3 card">
                                        <div class="card-header">
                                            Agregar nueva tarea al crontab
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">Para interpretar las líneas en el crontab tablas de configuración, sigue una sintaxis Crontab. Crontab tiene seis campos y los primeros cinco (1-5) campos definen la fecha y hora de ejecución. El último campo, es decir, el sexto campo, podría ser un nombre de usuario y / o tarea / trabajo / comando / script que se ejecutará.</p>
                                            <P class="text-muted">* &nbsp;* &nbsp;* &nbsp;* &nbsp;* &nbsp;&nbsp;&nbsp;&nbsp;COMANDO A EJECUTAR</p>
                                            <P class="text-muted">
                                            │ │ │ │ │<br>
                                            │ │ │ │ │<br>
                                            │ │ │ │ │ _________   Día de la semana (0 - 6) (0 es domingo, o use nombres)<br>
                                            │ │ │ │____________ Mes (1 - 12), * significa todos los meses<br>
                                            │ │ │______________  Día del mes (1 - 31), * significa todos los días<br>
                                            │ │________________  Hora (0 - 23), * significa cada hora<br>
                                            │___________________ Minuto (0 - 59), * significa cada minuto<br>
                                            </p>
                                            <form name="cron_new" id="cron_new" method="post" action="new.php">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <!-- <input placeholder="30 8 * * 7 home/path/to/command/the_command.sh >/dev/null 2>&1" type="text" class="mb-2 form-control" name="cron_new_line" id="cron_new_line"> -->
                                                        <input placeholder="" type="text" class="mb-2 form-control" name="cronInput" id="cronInput" value="* * * * *" placeholder="* * * * *" required="" autocorrect="off" autocapitalize="none">
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input placeholder="" type="text" class="mb-2 form-control" name="scriptPath" id="scriptPath" value="" placeholder="home/path/to/command/the_command.sh" required="" autocorrect="off" autocapitalize="none">
                                                    </div>

                                                    <div class="col-sm-12">
                                                        <h2 id="cronsTrueExpression" class="text-secondary text-center" style="color: #6c757d!important;">Cada minuto</h2>
                                                        <div id="iemdiv" class="mt-3 invalid-feedback text-center text-success">Expresión de cron no válida</div>
                                                        <button type="submit" class="btn btn-primary btn-new-cron" disabled>Agregar</button>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="<?php echo $display_text; ?>">
                            <div class="col-md-12">
                                <div class="main-card card">
                                    <div class="card-body table-responsive">
                                        <div class="alert alert-success fade show" role="alert">
                                            <h4 class="alert-heading">Hecho!</h4>
                                            <p>La tarea se ha agregado al crontab del servidor.</p>
                                            <hr>
                                        </div>
                                        <?php
                                        /**
                                         * Contenido
                                         */
                                        ?>
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
