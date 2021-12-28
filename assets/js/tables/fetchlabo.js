tablaLaboratorio = $('#datatable-json').DataTable({
    language: { url: "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json" },
    ajax:{
        url: './ajax.php',
        type: 'POST',
        data: {solicitud:'l'},
        dataSrc:""
    },
    columns: [
        { "data": "visit_id" },
        { "data": "area_id" },
        { "data": "nombre_cliente" },
        { "data": "nombre_area" },
        {
            "data": null,
            render:function(data, type, row)
            {
                return '<input type="time" id="visit'+data['visit_id']+'area'+data['area_id']+'" name="departure_time_area" class="text-sm p-1.5 m-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">';
            },
            "targets": -1
        },
        {
            "data": null,
            render:function(data, type, row)
            {
                return '<button type="button" name="actualizar" class="flex items-center justify-between px-2 py-2 text-base font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray" onclick="actualizar('+data['visit_id']+','+data['area_id']+')"><i class="far fa-calendar-check"></i></button>';
            },
            "targets": -1
        }
    ],
    responsive: true,
    processing: true,
    'columnDefs' : [
        //hide the second & fourth column
        { 'visible': false, 'targets': [0,1] }
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