<?php
include "api/init.php";
include "include/header-login.php";
?>    
                <div class="app-main_fullwith">
                    <div class="app-main__inner">
                        <div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="fa fa-fw icon-gradient bg-malibu-beach" aria-hidden="true" title="Copy to use key"></i>
                                    </div>
                                    <div>Inicie sesión
                                        <div class="page-title-subheading">
                                        Inicie sesión con su cuenta Microsoft (Correo DBS)
                                        </div>
                                    </div>
                                </div>
                                <div class="page-title-actions">
                                </div>    
                            </div>
                        </div>            
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-body" style="text-align: center;">
                                        <h5 class="card-title">
                                            <!-- Titulo -->
                                        </h5>
                                        
                                        <?php
                                        echo $buttonLogin;
                                        ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="app-wrapper-footer" style="position: absolute;width: 100%;bottom: 0;">
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
include "include/footer-login.php";
?>