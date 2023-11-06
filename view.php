<?php
include "include/header.php";

$host_prefix_default = $helper->get_config("host_prefix_default");
$helper->host = $helper->get_config("{$host_prefix_default}host");
$helper->port = $helper->get_config("{$host_prefix_default}port");
$helper->username = $helper->get_config("{$host_prefix_default}username");
$helper->password = $helper->get_config("{$host_prefix_default}password");

$crontab = new crontab\Ssh2_crontab_manager($helper->host, $helper->port, $helper->username, $helper->password);

/**
 * *Leer el conjobs
 */
$crontab->read_cronjobs_file($helper->host);
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
                                        <div class="page-title-subheading">Ver tareas instaladas.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>   


                        <div class="row">
                            <div class="col-md-12">
                                <div class="main-card mb-3 card">

                                    <div class="card-header">
                                        Tareas cron instaladas.
                                    </div>
                                    <div class="card-body table-responsive">

                                        <table class='table table-hover table-bordered table-striped text-center' id='tareas-crontab' data-page-length='50'>
                                            <thead>
                                                <tr>
                                                    <th scope='col'>Tarea</th>
                                                    <th scope='col'>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach($crontab->cronjobs_content as $task)
                                                {
                                                    if($task != "")
                                                    {
                                                        $buttons = "";
                                                        $result = basename($task);

                                                        $class_muted = "";
                                                        $comment_button_text = "Comentar";
                                                        $class_button_comment = "btn-outline-light";
                                                        $task_replaced = $task;
                                                        if (strpos($task, "#") === 0)
                                                        {
                                                            $class_muted = "text-muted opacity-6";
                                                            $comment_button_text = "Descomentar";
                                                            $class_button_comment = "btn-outline-info";
                                                            $task_replaced = str_replace("#", "", $task);
                                                        }
                                                            
                                                        $buttons .= "<button class='mb-2 mr-2 btn-transition btn {$class_button_comment} toggle-comment-cron' data-cron='{$task}'>{$comment_button_text}</button>
                                                                     <button class='mb-2 mr-2 btn-transition btn btn-outline-danger delete-cron' data-cron='{$task}'>Eliminar</button>";

                                                        echo "<tr>";
                                                        echo "  <th class='{$class_muted}'>{$task_replaced}</th>";
                                                        echo "  <td>{$buttons}</td>";
                                                        echo "</tr>";
                                                    }
                                                    
                                                }
                                                ?>
                                            </tbody>
                                        </table>
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
