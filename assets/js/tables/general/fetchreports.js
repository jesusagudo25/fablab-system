tablaReportes = $('#datatable-json').DataTable({
    language: { url: "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json" },
    "processing": true,
    "serverSide": true,
    "ajax": "./functions.php",
    "columnDefs": 
    [
        {
            "data": null,
            render:function(data, type, row)
            {
                return '<div class="flex items-center space-x-4"><a href="./download.php?reporte='+data[0]+'.pdf" target="_blank" class="flex items-center justify-between px-2 py-2 text-lg font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray" ><i class="fas fa-file-pdf"></i></a><button value="'+data[0]+'" type="button" name="borrar" class="flex items-center justify-between px-2 py-2 text-lg font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray .btn-borrar" onclick="borrar(this)"><i class="fas fa-trash-alt"></i></button></div>';
            },
            "targets": 5
        },
        { "visible": false,  "targets": [ 0 ] }
    ]
});

const formulario = document.querySelector('form'),
        start_date = document.querySelector('input[name="start"]'),
        end_date = document.querySelector('input[name="end"]');

        const elem = document.getElementById('foo');
const rangepicker = new DateRangePicker(elem, {
    format: 'yyyy/mm/dd',
    language: "es"
});

formulario.addEventListener('submit', e =>{
    e.preventDefault();

    let datos = new FormData(formulario);

    Swal.fire({
        title: 'Cargando...',
        html: 'Espere por favor...',
        allowEscapeKey: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading()
        }
    });

    $.ajax({
        url: "./functions.php",
        type: "POST",
        datatype:"json",
        data:  {
            solicitud: "c",
            start_date: datos.get('start'),
            end_date: datos.get('end')
        },
        success: function(data) {
            tablaReportes.ajax.reload();
            Swal.close();
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
            rangepicker.setDates({clear:true});
        }
    });

});

function borrar(e) {

    Swal.fire({
        title: 'Advertencia',
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
                    Toastify({
                        text: "Reporte eliminado!",
                        duration: 3000,
                        style: {
                            background: '#10B981'
                        }
                    }).showToast();
                }
            });
        }
    });

}