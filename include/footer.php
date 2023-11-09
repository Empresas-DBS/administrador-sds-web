        </div>
    </div>
    
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script type="text/javascript" src="./assets/scripts/jquery.min.js"></script>
    <script type="text/javascript" src="./assets/scripts/admin.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="./assets/scripts/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="./assets/scripts/bootstrap-datepicker.es.min.js" charset="UTF-8"></script>
    <script type="text/javascript" src="./assets/scripts/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="./assets/scripts/numeric-comma.js"></script>
    <script type="text/javascript" src="./assets/scripts/dropzone.min.js"></script>
    <script type="text/javascript" src="./assets/scripts/scripts.js"></script>
    <script type="text/javascript" src="./assets/scripts/autocomplete.js"></script>
    <script type="text/javascript" src="./assets/scripts/cronstrue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>

    <script>
        var oTable = $('.datepicker').datepicker({
            format: "dd/mm/yy",
            language: "es",
        });

        var oTable = $('.datepicker2').datepicker({
            format: "yyyy-mm",
            language: "es",
            viewMode: "months", 
            minViewMode: "months",
            onClose: function(dateText, inst) { 
                $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
            }
        });
    </script>
</body>
</html>

<?php
$sds->dbWeb1->dbClose();
?>