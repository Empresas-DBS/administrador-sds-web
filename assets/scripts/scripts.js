
jQuery.sendMail = function(type, date, custom_message)
{
    $(".working-icon").removeClass("d-none");
    let domain = $.getDomain();
    let response;
    
    if(custom_message == null)
    {
        message = ""
    }
    else
    {
        message = custom_message
    }

    $.ajax({
        url: domain + "/inc/sendMail.php",
        type: "post",
        dataType: "JSON",
        data: {type : type, date: date, message: message},
        success: function (data, status)
        {
            console.log(data);
            if(data == "SENT")
            {
                $(".working-icon").addClass("d-none");
            }
            else
            {
                $(".working-icon").addClass("d-none");
            }
        },
        error: function (xhr, desc, err)
        {
            alert("No se puede enviar el correo en este momento, intentelo más tarde.");
        }
    });
}

jQuery.fetchData = function()
{
    $.ajax({
        url: domain + "/inc/loadData.php",
        type: "post",
        dataType: "JSON",
        data: {type : type, date: date, message: message},
        success: function (data, status)
        {
            console.log(data);
            if(data == "SENT")
            {
                $(".working-icon").addClass("d-none");
            }
            else
            {
                $(".working-icon").addClass("d-none");
            }
        },
        error: function (xhr, desc, err)
        {
            alert("No se puede enviar el correo en este momento, intentelo más tarde.");
        }
    });
}

jQuery.getDomain = function()
{
    var pathArray = window.location.pathname.split('/');
    pathArray.shift();
    pathArray.pop();
    domain = location.protocol + '//' + location.host;
    for(i=0; i < pathArray.length; i++)
    {
        domain += '/' + pathArray[i];
    }
    // domain = location.protocol + '//' + location.host + '/' + location.pathname + '/';
    return domain;
}

jQuery.updateProgress = function()
{
    if($(".progress-bar").length)
    {
        var errors = document.getElementsByClassName("icon-error").length,
            rows = $(".link-row-no").length;

        var superTotal = (parseInt(errors) * 100) / parseInt(rows),
            errorsInPercentage = Math.ceil(superTotal);

        if(errorsInPercentage == 0)
        {
            $(".progress-bar").removeClass("bg-warning");
            $(".progress-bar").addClass("bg-success");
        }

        $(".progress-bar").attr("aria-valuenow", (100 - parseInt(errorsInPercentage)));
        $(".progress-bar").css("width", (100 - parseInt(errorsInPercentage)) + "%");
    }
}

jQuery.formatNumber = function(num, add_cents = false) 
{
    if (!num || num == 'NaN') return '-';
    if (num == 'Infinity') return '&#x221e;';
    num = num.toString().replace(/\$|\,/g, '');
    if (isNaN(num))
        num = "0";
    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num * 100 + 0.50000000001);
    cents = num % 100;
    num = Math.floor(num / 100).toString();
    if (cents < 10)
        cents = "0" + cents;
    for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3) ; i++)
        num = num.substring(0, num.length - (4 * i + 3)) + '.' + num.substring(num.length - (4 * i + 3));

    if(add_cents)
    {
        return (((sign) ? '' : '-') + num + ',' + cents);
    }
    else
    {
        return (((sign) ? '' : '-') + num );
    }
}

$(document).ready(function(){
    // $('select').selectize({
    //     sortField: 'text'
    // });

    let data_offset = 200
    $.updateProgress();

    var Body = $('body');
    Body.addClass('preloader-site');
    $(window).load(function() {
        $('.preloader-wrapper').fadeOut();
        $('body').removeClass('preloader-site');
    });

    $('#table-data-show1').DataTable({
        dom: 'RlBfrtip',
        pageLength: 100,
        buttons: [
            {
                extend: 'excel',
                title: null,
                footer: false,
                exportOptions: {
                    columns: [ 3, 4, 5, 6, 7, 8 ],
                }
            },
        ],
        paging: true,
        searching: true,
        language: {
            url: 'assets/scripts/dataTable.spanish.json',
        },
        columnDefs: [
            { type: 'numeric-comma', targets: 0 },
        ],
        columnDefs: [
            { "visible": false, "targets": 0 },
            { "visible": false, "targets": 1},
            { "visible": false, "targets": 2},
        ],
    });

    $('#tareas-crontab').DataTable({
        dom: "<'row'<'col-sm-3'l><'col-sm-3'f><'col-sm-6'p>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        language: {
            url: 'assets/scripts/dataTable.spanish.json',
        },
        columnDefs: [
            { type: 'numeric-comma', targets: 0 },
        ]
    });

    $('#table-data-show3').DataTable({
        dom: "<'row'<'col-sm-3'l><'col-sm-3'f><'col-sm-6'p>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        language: {
            url: 'assets/scripts/dataTable.spanish.json',
        },
        columnDefs: [
            { type: 'numeric-comma', targets: 0 },
            { type: 'numeric-comma', targets: 1 },
            { type: 'numeric-comma', targets: 2 },
            { type: 'numeric-comma', targets: 3 },
            { type: 'numeric-comma', targets: 4 },
            { type: 'numeric-comma', targets: 5 },
            { type: 'numeric-comma', targets: 6 },
        ]
    });

    // $(window).scroll(function(){
    //     var position = $(window).scrollTop();
    //     var bottom = $(document).height() - $(window).height() - 25; // resto 25px para aproximar el final de la tabla

    //     if(position >= bottom) {
    //         $.fetchData(data_offset)
    //     }
    // });


    
    $("#scriptPath").keyup(function(e){
        if(($("#cronInput").val() != "")&&($("#scriptPath").val() != ""))
            $(".btn-new-cron").prop("disabled", false);
        else
            $(".btn-new-cron").prop("disabled", true);
    });

    $(".delete-cron").click(function(){
        let $obj = $(this),
            task = $obj.data("cron")

        $obj.prop("disabled", true)
        $.ajax({
            url: $.getDomain() + "/ajax/ajax_delete_cron.php",
            type: "post",
            dataType: "JSON",
            data: {task : task},
            success: function (data, status)
            {
                if(data == "deleted")
                {
                    $obj.closest("tr").fadeOut();
                }
            },
            error: function (xhr, desc, err)
            {
                $obj.prop("disabled", false)
                console.log("Ocurrio un error interno, intentelo más tarde.");
            }
        });
    });

    $(".toggle-comment-cron").click(function(){
        let $obj = $(this),
            task = $obj.data("cron")

        $obj.prop("disabled", true)
        $.ajax({
            url: $.getDomain() + "/ajax/ajax_toggle_comment_cron.php",
            type: "post",
            dataType: "JSON",
            data: {task : task},
            success: function (data, status)
            {
                if((data == "add_comment")||(data == "del_comment"))
                {
                    if(data == "add_comment")
                    {
                        $obj.closest("td")
                            .prev("th")
                            .addClass("text-muted opacity-6")
                        $obj.removeClass("btn-outline-light")
                            .addClass("btn-outline-info")
                            .text("Descomentar")
                    }
                    else if(data == "del_comment")
                    {
                        $obj.closest("td")
                            .prev("th")
                            .removeClass("text-muted opacity-6")
                        $obj.removeClass("btn-outline-info")
                            .addClass("btn-outline-light")
                            .text("Comentar")
                    }
                    $obj.prop("disabled", false)
                }
            },
            error: function (xhr, desc, err)
            {
                $obj.prop("disabled", false)
                console.log("Ocurrio un error interno, intentelo más tarde.");
            }
        });
    });

    $(".btn-set-default").click(function(){
        let $obj = $(this),
            prefix = $obj.data("prefix")

        $.ajax({
            url: $.getDomain() + "/ajax/ajax_set_default.php",
            type: "post",
            dataType: "JSON",
            data: {prefix : prefix},
            success: function (data, status)
            {
                if(data == "changed")
                {
                    $(".btn-set-default").text("Seleccionar")
                    $(".btn-set-default").addClass("btn-outline-info")
                    $(".btn-set-default").removeClass("btn-outline-success")
                    $(".btn-set-default").addClass("btn-outline-info")
                    $(".btn-set-default").prop("disabled", false);
                    $obj.removeClass("btn-outline-info")
                        .addClass("btn-outline-success")
                        .text("Seleccionado")

                }
            },
            error: function (xhr, desc, err)
            {
                $obj.prop("disabled", false)
                console.log("Ocurrio un error interno, intentelo más tarde.");
            }
        });
    });

    $(".select-set-default").change(function(){
        let $obj = $(this),
            prefix = $obj.val()

        $.ajax({
            url: $.getDomain() + "/ajax/ajax_set_default.php",
            type: "post",
            dataType: "JSON",
            data: {prefix : prefix},
            success: function (data, status)
            {
                if(data == "changed")
                {
                    location.reload()
                }
            },
            error: function (xhr, desc, err)
            {
                console.log("Ocurrio un error interno, intentelo más tarde.");
            }
        });
    });

    $(".btn-edit").click(function()
    {
        let $obj = $(this),
            prefix = $obj.data("prefix")

        $("#hidden_prefix").val(prefix)
        $("#form_edit").submit()
    });
});


var contentTable = $('#contentTable');
contentTable.hide();
$('#consultarServerSide').on('click', function() {
    var SUMAR = $('#sumar').val();
    datatableServerSide(SUMAR);
});

function datatableServerSide(SUMAR){

    var contentTable = $('#contentTable');
    contentTable.hide();

    if ($.fn.DataTable.isDataTable('#table-data-show2')) {
        $('#table-data-show2').DataTable().destroy();
    }

    var search_canal = $('#search_canal').val();
    var search_sku = $('#sku').val();

    var table = $('#table-data-show2').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": true,
        "ajax": {
            "url": "ajax/ajax_search_server_side.php",
            "type": "POST",
            "data": {
                search_sku: search_sku,
                search_canal: search_canal,
            }
        },
        "drawCallback": function(settings) {
            contentTable.show();
        },
        "order": [[ 3, "asc" ]],
        "columns": [
            {"data": "id"},
            {"data": "sbs_no"},
            {"data": "store_no"},
            {"data": "alu"},
            {
                "data": "qty",
                "render": function(data, type, row) {
                    if(SUMAR){
                        return row.qty - 1;
                    }else{
                        return row.qty;
                    }
                }
            },
            {
                "data": "seguridad",
                "render": function(data, type, row) {
                    if(SUMAR){
                        return row.seguridad - 1;
                    }else{
                        return row.seguridad;
                    }
                }
            },
            {"data": "desde"},
            {"data": "hasta"},
            {"data": "canal"},
            {
                "data": null,
                "render": function(data, type, row) {
                    return `
                        <button class="mb-2 mr-2 btn-transition btn btn-outline-primary btn-edit2" 
                            data-alu="${row.alu}" data-canal="${row.canal}">
                            <i class="fa fa-fw" aria-hidden="true" title=""></i>
                        </button>
                        <button class="mb-2 mr-2 btn-transition btn btn-outline-danger btn-delete2" 
                            data-alu="${row.alu}" data-canal="${row.canal}">
                            <i class="fa fa-fw" aria-hidden="true" title=""></i>
                        </button>
                    `;
                }
            }
        ],
        "columnDefs": [
            { "visible": false, "targets": [0, 1, 2] }
        ],
        "language": {
            url: 'assets/scripts/dataTable.spanish.json',
        },
    });

    $('#table-data-show2_filter input').on('input', function() {
        // Actualizar el valor del campo #search_sku
        $('#search_sku').val(this.value);
        table.search(this.value).draw();
    });

    $('#table-data-show2').on('click', '.btn-edit2', function(){
        let alu = $(this).data("alu"),
            canal = $(this).data("canal")

        location.href = "new.php?alu=" + alu + "&canal=" + canal;
    });

    $('#table-data-show2').on('click', '.btn-delete2', function(){
        let alu = $(this).data("alu"),
            canal = $(this).data("canal"),
            $obj = $(this)

        $.delete(alu, canal, $obj);
    });
}

function downloadExcel(){
    //btn downloadExcel
    let downloadExcel = $('#downloadExcel');
    downloadExcel.text("Descargando...");
    downloadExcel.attr('onclick', '');

    $.ajax({
        url: $.getDomain() + "/ajax/ajax_download_excel.php",
        type: "post",
        data: {},
        success: function (data) {
            // Crear un enlace temporal y simular clic para descargar el archivo
            var blob = new Blob([data], { type: 'text/csv' });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = "Consolidado_SDS.csv";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            //btn downloadExcel
            downloadExcel.text("Excel");
            downloadExcel.attr('onclick', 'downloadExcel()');
        },
        error: function () {
            console.log("Error en la descarga del archivo. Inténtelo más tarde.");
        }
    });
}