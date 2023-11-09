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

    return domain;
}

jQuery.delete = function(alu, canal, $obj)
{
    let domain = $.getDomain();

    $.ajax({
        url: domain + "/ajax/delete.php",
        type: "post",
        dataType: "JSON",
        data: {alu : alu, canal : canal},
        success: function (data, status)
        {
            if(data == "OK_DELETE")
            {
                $obj.closest("tr").fadeOut()
            }
            else
            {
                alert("No se pudo eliminar el SKU.")
            }
        },
        error: function (xhr, desc, err)
        {
            
        }
    });
}

$(document).ready(function(){
    $(".btn-new").click(function(){
        let sbs_no = $("[name='sbs_no']").val(),
            store_no = $("[name='store_no']").val(),
            alu = $("[name='alu']").val(),
            qty = $("[name='qty']").val(),
            seguridad = $("[name='seguridad']").val(),
            canal = $("[name='canal']").val(),
            desde = $("[name='desde']").val(),
            hasta = $("[name='hasta']").val()

        if((sbs_no != "")&&(store_no != "")&&(alu != "")&&(qty != "")&&(seguridad != "")&&(canal != ""))
        {
            $("[name='form_new']").submit();
        }
        
    });

    $(".btn-edit").click(function(){
        let alu = $(this).data("alu"),
            canal = $(this).data("canal")

        location.href="new.php?alu=" + alu + "&canal=" + canal;
    });

    $(".btn-delete").click(function(){
        let alu = $(this).data("alu"),
            canal = $(this).data("canal"),
            $obj = $(this)

        $.delete(alu, canal, $obj)
    });
});