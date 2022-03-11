tablaLaboratorio = $('#datatable-json').DataTable({
    language: { url: "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json" },
    "processing": true,
    "serverSide": true,
    "ajax": "./ajax.php",
    "columnDefs": 
    [
        {
            "data": null,
            render:function(data, type, row)
            {
                return '<input type="time" id="visit'+data[0]+'area'+data[1]+'" class="text-sm p-1.5 m-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" min="08:00" max="16:00">';
            },
            "targets": 4
        },
        {
            "data": null,
            render:function(data, type, row)
            {
                return '<button type="button" name="actualizar" class="flex items-center justify-between px-2 py-2 text-lg font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray" onclick="actualizar('+data[0]+','+data[1]+')"><i class="far fa-calendar-check"></i></button>';
            },
            "targets": 5
        },
        { "visible": false,  "targets": [ 0,1 ] }
    ]
});

function actualizar(v,a) {
    const hora = document.querySelector('#visit'+v+'area'+a);

    $.ajax({
        url: "./ajax.php",
        type: "POST",
        datatype:"json",
        data:  {
            solicitud: "u",
            visit_id: v,
            area_id: a,
            departure_time: hora.value
        },
        success: function(data) {
            tablaLaboratorio.ajax.reload();
            Toastify({
                text: "Visita actualizada",
                duration: 3000,
                style: {
                    background: '#10B981'
                }
            }).showToast();
        }
    });
}