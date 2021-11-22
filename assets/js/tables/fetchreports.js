tablaReportes = $('#datatable-json').DataTable({
    "ajax":{
        url: './functions.php',
        type: 'POST',
        data: {solicitud:'r'},
        dataSrc:""
    },
    columns: [
        { "data": "report_id" },
        { "data": "month" },
        { "data": "autor" },
        { "data": "start_date" },
        { "data": "end_date" },
        {
            "data": null,
            render:function(data, type, row)
            {
                return '<div class="flex items-center space-x-4"><a href="./download.php?reporte='+data['report_id']+'.pdf" target="_blank" class="flex items-center justify-between px-2 py-2 text-base font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray" ><i class="fas fa-file-pdf"></i></a><button value="'+data['report_id']+'" type="button" name="borrar" class="flex items-center justify-between px-2 py-2 text-base font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray .btn-borrar" onclick="borrar(this)"><i class="fas fa-trash-alt"></i></button></div>';
            },
            "targets": -1
        }
    ],
    responsive: true,
    processing: true,
    'columnDefs' : [
        //hide the second & fourth column
        { 'visible': false, 'targets': [0] }
    ]
});

const formulario = document.querySelector('form'),
        start_date = document.querySelector('input[name="start_date"]'),
        end_date = document.querySelector('input[name="end_date"]');

formulario.addEventListener('submit', e =>{
    e.preventDefault();

    let datos = new FormData(formulario);

    $.ajax({
        url: "./functions.php",
        type: "POST",
        datatype:"json",
        data:  {
            solicitud: "c",
            start_date: datos.get('start_date'),
            end_date: datos.get('end_date')
        },
        success: function(data) {
            tablaReportes.ajax.reload();
            Swal.fire({
                title: 'El reporte se ha generado!',
                allowOutsideClick: false,
                icon: 'success',
                confirmButtonColor: '#3b82f6',
                footer: `<a class="flex items-center justify-between swal2-deny swal2-styled" target="_blank" href="./download.php?reporte=${data}" id="pdf">
        </svg>
        <i class="fas fa-file-pdf mr-3"></i>
                  <span>Descargar PDF</span>
                </a>`
            });
            start_date.value = '';
            end_date.value = '';
        }
    });
});

function borrar(e) {

    Swal.fire({
        title: '¿Estás seguro?',
        text: "El reporte será eliminado y no podrá ser recuperado.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#10B981',
        cancelButtonColor: '#EF4444',
        confirmButtonText: 'Si, borrar ahora!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "./functions.php",
                type: "POST",
                datatype:"json",
                data:  {
                    solicitud: "d",
                    id: e.value
                },
                success: function(data) {
                    tablaReportes.ajax.reload();
                    Swal.fire({
                            title: 'Eliminado!',
                            text:  'El reporte ha sido eliminado.',
                            icon: 'success',
                            confirmButtonColor: '#10B981'
                        }
                    )
                }
            });
        }
    })

}