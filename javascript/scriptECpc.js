//pedimos los datos para obtener los conductores
$(document).ready(function() {
    $.ajax({
        url: "ECpc.php",
        type: "GET",
        dataType: "json",
        success: function(personalpa) {
            console.log("Datos recibidos: ", personalpa);
            let select  = $("#select-repartidores");
            select.empty();//limpismod el select
            select.append('<option value="">REPARTIDOR</option>');
            personalpa.forEach(function(repartidor) {
                select.append(`<option value="${repartidor.nom}">${repartidor.nom}</option>`);
            });
        },
        error: function(xhr, status, error) {
            console.error("Error al obtener los conductores:", error.responseText);
        }
    });
});