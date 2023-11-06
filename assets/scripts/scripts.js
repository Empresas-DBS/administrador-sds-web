
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
        paging: false,
        searching: true,
        language: {
            url: 'assets/scripts/dataTable.spanish.json',
        },
        columnDefs: [
            { type: 'numeric-comma', targets: 0 },
        ]
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