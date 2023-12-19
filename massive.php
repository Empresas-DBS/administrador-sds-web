<?php
    include "include/header.php";
?>
    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="pe-7s-upload icon-gradient bg-mean-fruit">
                            </i>
                        </div>
                        <div>Carga masiva de stock de seguridad e iluminación
                            <div class="page-title-subheading">Agrega registros al sistema para sincronizar.</div>
                            <br>
                            <a href="javascript:void(0)" class="btn btn-primary btn-small" onclick="borrarRegistros()">Eliminar todos los registros</a>
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
                            <form name="form_massive" id="form_massive" onSubmit="return false;">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label center-vertical-div">Archivo:</label>
                                    <div class="col-sm-6">
                                        <input type="file" name="archivo_excel" id="archivo_excel" placeholder="Archivo" required accept=".xlsx">
                                    </div>
                                    <div class="col-sm-4 center-vertical-div">
                                        <button type="submit" name="submit" id="submit" onclick="cargaMasiva()" class="btn btn-primary">Cargar Archivo</button>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-sm-12">
                                <a href="./assets/excel/formatosds.xlsx">Descargar Formato Excel</a>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="list-group" id="list_massive"></div>
                                </div>
                            </div>
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
