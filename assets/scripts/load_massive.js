function cargaMasiva(){
    var formMassive = document.getElementById("form_massive");
    var file = document.getElementById("archivo_excel").files[0];
    //validar que se haya seleccionado un archivo
    if(file == null){
        alert("Debe seleccionar un archivo");
        return false;
    }

    //validar formato de archivo
    var extension = file.name.split(".").pop();
    if(extension != "xlsx"){
        alert("El archivo debe ser de tipo Excel (.xlsx)");
        return false;
    }

    var form = new FormData();
    form.append("form_massive", formMassive);
    form.append("archivo_excel", file);
    $.ajax({
        url: $.getDomain() + "/ajax/ajax_load_massive.php",
        type: "POST",
        data: form,
        contentType: false,
        processData: false,
        beforeSend: function (){
            document.getElementById("list_massive").innerHTML = '<div><img src="./assets/images/loader.gif" width="30"> Cargando archivo...</div>';
        },
        success: function (data, status){
            data = JSON.parse(data);
            document.getElementById("list_massive").innerHTML = "";
            var h3 = document.createElement("h3");
            h3.innerHTML = "Resumen de carga masiva";
            document.getElementById("list_massive").appendChild(h3);

            var table = document.createElement("table");
            table.setAttribute("id", "table_massive");
            table.setAttribute("class", "table");

            var thead = document.createElement("thead");
            var trHead = document.createElement("tr");

            var columns = [];
            data.forEach(function (element) {
                for (var key in element) {
                    if (element.hasOwnProperty(key) && !columns.includes(key)) {
                        columns.push(key);

                        var th = document.createElement("th");
                        th.innerHTML = key;
                        trHead.appendChild(th);
                    }
                }
            });

            thead.appendChild(trHead);
            table.appendChild(thead);

            var tbody = document.createElement("tbody");
            data.forEach(function (element) {
                var trBody = document.createElement("tr");
                //class msg_type tr body
                trBody.setAttribute("class", "list-group-item-"+element["msg_type"]);
                if(element["msg_type"] == "success"){
                    trBody.style.backgroundColor = "#c8eedb";
                }else{
                    trBody.style.backgroundColor = "#f4c2ce";
                }

                columns.forEach(function (column) {
                    var td = document.createElement("td");
                    td.innerHTML = element[column];
                    trBody.appendChild(td);
                });

                tbody.appendChild(trBody);
            });

            table.appendChild(tbody);

            document.getElementById("list_massive").appendChild(table);
            document.getElementById("list_massive").scrollTop = document.getElementById("list_massive").scrollHeight;
            document.getElementById("archivo_excel").value = "";
            document.getElementById("form_massive").reset();
            $('#table_massive').DataTable({
                //download excel
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: null,
                        footer: false,
                        className: 'btn btn-success',
                        filename: 'Resumen de carga masiva',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    }
                ],
                pageLength: 5,
                bLengthChange: false,
                info: false,
                paging: true,
                searching: true,
                language: {
                    url: 'assets/scripts/dataTable.spanish.json',
                    info: "Resumen de carga masiva",
                },
                columnDefs: [
                    { type: 'numeric-comma', targets: 0 },
                    { targets: [0], title: "N°" },
                    { targets: [1], title: "SKU" },
                    { targets: [2], title: "Canal" },
                    { targets: [3], title: "Status" },
                    { targets: [4], visible: false, searchable: false },
                ]
            });
        },
        error: function (xhr, desc, err){
            console.log("Ocurrio un error al cargar el archivo");
        }
    });
}

function borrarRegistros(){
    if(confirm("¿Está seguro de borrar todos los registros? \nEsta acción no se puede deshacer")){
        $.ajax({
            url: $.getDomain() + "/ajax/ajax_delete_massive.php",
            type: "POST",
            data: {
                "delete_massive": true
            },
            beforeSend: function (){
                document.getElementById("list_massive").innerHTML = '<div><img src="./assets/images/loader.gif" width="30"> Borrando registros...</div>';
            },
            success: function (data, status){
                data = JSON.parse(data);
                document.getElementById("list_massive").innerHTML = "";
                var h3 = document.createElement("h3");
                h3.innerHTML = "";
                document.getElementById("list_massive").appendChild(h3);

                var div = document.createElement("div");
                div.setAttribute("class", "alert alert-" + data[0].msg_type);
                div.innerHTML = data[0].msg;
                document.getElementById("list_massive").appendChild(div);
            },
            error: function (xhr, desc, err){
                console.log("Ocurrio un error al borrar los registros");
            }
        });
    }
}