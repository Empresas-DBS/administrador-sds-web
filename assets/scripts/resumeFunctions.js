var domain = location.protocol + '//' + location.host + "/dashboard-transacciones/";

jQuery.getStoreData = function(type, company, date)
{
    console.log(domain + "inc/getStoresData.php");
    $.ajax({
        url: domain + "/inc/getStoresData.php",
        type: "post",
        dataType: "JSON",
        data: {type : type, company : company, date : date},
        success: function (data, status)
        {
            console.log(data);
            if(type == "success")
            {
                jQuery(".data-number-" + type).html("").text(data.resultSuccess);
            }
            if(type == "danger")
            {
                jQuery(".data-number-" + type).html("").text(data.resultErrors);
            }
        },
        error: function (xhr, desc, err)
        {
            alert("No se puede obtener el numero de cajas cuadradas y no cuadradas para la empresa seleccionada.");
            jQuery(".data-number-" + type).html("").text("-");
        }
    });
}

jQuery.getGraphStoreClose = function(company, date)
{
    $.ajax({
        url: domain + "/inc/getGraphStoreClose.php",
        type: "post",
        dataType: "JSON",
        data: {company : company, date : date},
        success: function (data, status)
        {
            console.log("data: ");
            console.log(data);
            $("#td_company").text(data.company_stores0);
            $("#td_stores").text(data.total_stores0);
            $("#td_rpro").text(data.total_stores1);
            $("#td_tpaso").text(data.total_stores2);
            $(".row-company").addClass(data.error_class);
        },
        error: function (xhr, desc, err)
        {
            alert("No se puede obtener el resumen de montos para la empresa selecionada.");
        }
    });
}

jQuery.getTableMonthValues = function(company, date, number_of_months)
{
    // var source = "tiendas";
    $.ajax({
        url: domain + "/inc/getTableMonthValues.php",
        type: "post",
        dataType: "JSON",
        data: {company : company, date : date, number_of_months : number_of_months},
        success: function (data, status)
        {
            if((data.total_tienda0 != data.total_rpro0)||(data.total_tienda0 != data.total_tpaso0)||(data.total_rpro0 != data.total_tpaso0))
            {
                $("#table_month_values>tbody").append("<tr class='error'><td>" + data.mes0 + "</td><td>" + data.total_tienda0 + "</td><td>" + data.total_rpro0 + "</td><td>" + data.total_tpaso0 + "</td></tr>");
            }
            else
            {
                $("#table_month_values>tbody").append("<tr><td>" + data.mes0 + "</td><td>" + data.total_tienda0 + "</td><td>" + data.total_rpro0 + "</td><td>" + data.total_tpaso0 + "</td></tr>");
            }

            if((data.total_tienda1 != data.total_rpro1)||(data.total_tienda1 != data.total_tpaso1)||(data.total_rpro1 != data.total_tpaso1))
            {
                $("#table_month_values>tbody").append("<tr class='error'><td>" + data.mes1 + "</td><td>" + data.total_tienda1 + "</td><td>" + data.total_rpro1 + "</td><td>" + data.total_tpaso1 + "</td></tr>");
            }
            else
            {
                $("#table_month_values>tbody").append("<tr><td>" + data.mes1 + "</td><td>" + data.total_tienda1 + "</td><td>" + data.total_rpro1 + "</td><td>" + data.total_tpaso1 + "</td></tr>");
            }
            
            if((data.total_tienda2 != data.total_rpro2)||(data.total_tienda2 != data.total_tpaso2)||(data.total_rpro2 != data.total_tpaso2))
            {
                $("#table_month_values>tbody").append("<tr class='error'><td>" + data.mes2 + "</td><td>" + data.total_tienda2 + "</td><td>" + data.total_rpro2 + "</td><td>" + data.total_tpaso2 + "</td></tr>");
            }
            else
            {
                $("#table_month_values>tbody").append("<tr><td>" + data.mes2 + "</td><td>" + data.total_tienda2 + "</td><td>" + data.total_rpro2 + "</td><td>" + data.total_tpaso2 + "</td></tr>");
            }
            
            
        },
        error: function (xhr, desc, err)
        {
            alert("No se puede obtener el total de los últimos " + number_of_months + " meses.");
        }
    });
}