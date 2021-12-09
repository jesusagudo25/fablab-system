tablaObservaciones = $('#datatable-json').DataTable({
    language: { url: "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json" },
    ajax:{
        url: './functions.php',
        type: 'POST',
        data: {solicitud:'o'},
        dataSrc:""
    },
    columns: [
        { "data": "observation_id" },
        { "data": "autor" },
        { "data": "descripcion" },
        { "data": "fecha" },
        {
            "data": null,
            render:function(data, type, row)
            {
                return '<div class="flex items-center space-x-4"> <button value="'+data['observation_id']+'" type="button" class="flex items-center justify-between px-2 py-2 text-base font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray btn-editar" onclick="editar(this)"><i class="fas fa-edit"></i></i></button><button value="'+data['observation_id']+'" type="button" name="borrar" class="flex items-center justify-between px-2 py-2 text-base font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray .btn-borrar" onclick="borrar(this)"><i class="fas fa-trash-alt"></i></button></div>';
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

const newObs = document.querySelector('#observacion'),
    closeModal = document.querySelectorAll('.close'),
    modal = document.querySelector('#modal'),
    guardar = document.querySelector('button[name="guardar"]'),
    description = document.querySelector('textarea[name="descripcion"]'),
    fecha= document.querySelector('input[name="fecha"]');

newObs.addEventListener('click', evt => {
    modal.classList.toggle('hidden');

    guardar.addEventListener('click', crear);

    closeModal.forEach( e =>{
        e.addEventListener('click', cerrarCrear);
    });

    function cerrarCrear(e){
        modal.classList.toggle('hidden');
        guardar.removeEventListener('click', crear);
        description.value = '';
        fecha.value = '';
        closeModal.forEach( e =>{
            e.removeEventListener('click', cerrarCrear);
        });
    }

    function crear(e) {
        modal.classList.toggle('hidden');

        $.ajax({
            url: "./functions.php",
            type: "POST",
            datatype:"json",
            data:  {
                solicitud: "c",
                description: description.value,
                date: fecha.value,
            },
            success: function(data) {
                description.value = '';
                fecha.value = '';
                tablaObservaciones.ajax.reload();
                guardar.removeEventListener('click', crear);
                closeModal.forEach( e =>{
                    e.removeEventListener('click', cerrarCrear);
                });
                Toastify({
                    text: "Observación agregada",
                    duration: 3000,
                    style: {
                        background: '#10B981'
                    }
                }).showToast();
            }
        });
    }

});

function editar(e){

    $.ajax({
        url: "./functions.php",
        type: "POST",
        datatype:"json",
        data:  {
            solicitud: "obs_id",
            id: e.value,
        },
        success: function(data) {
            description.value = data['description'];
            fecha.value = data['date'];
            modal.classList.toggle('hidden');

            guardar.addEventListener('click', actualizar);

            closeModal.forEach( e =>{
                e.addEventListener('click', cerrarActualizar);
            });

            function cerrarActualizar(e){
                modal.classList.toggle('hidden');
                guardar.removeEventListener('click', actualizar);
                description.value = '';
                fecha.value = '';
                closeModal.forEach( e =>{
                    e.removeEventListener('click', cerrarActualizar);
                });
            }

            function actualizar(evt) {
                modal.classList.toggle('hidden');

                $.ajax({
                    url: "./functions.php",
                    type: "POST",
                    datatype:"json",
                    data:  {
                        solicitud: "u",
                        description: description.value,
                        date: fecha.value,
                        id: e.value
                    },
                    success: function(data) {
                        description.value = '';
                        fecha.value = '';
                        tablaObservaciones.ajax.reload();
                        guardar.removeEventListener('click', actualizar);
                        closeModal.forEach( e =>{
                            e.removeEventListener('click', cerrarActualizar);
                        });
                        Toastify({
                            text: "Observación actualizada",
                            duration: 3000,
                            style: {
                                background: '#10B981'
                            }
                        }).showToast();
                    }
                });
            }
        }
    });
}

function borrar(e) {

    Swal.fire({
        title: '¿Estás seguro?',
        text: "La observación será eliminada y no podrá ser recuperada.",
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
                    tablaObservaciones.ajax.reload();
                    Toastify({
                        text: "Observación eliminada!",
                        duration: 3000,
                        style: {
                            background: '#10B981'
                        }
                    }).showToast();
                }
            });
        }
    })
}

