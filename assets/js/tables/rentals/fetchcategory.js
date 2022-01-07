tablaCategorias = $('#datatable-json').DataTable({
    language: { url: "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json" },
    ajax:{
        url: './functions.php',
        type: 'POST',
        data: {solicitud:'c'},
        dataSrc:""
    },
    dom: 'Brtip',
    initComplete:function( settings, json){
        document.querySelector('.dt-buttons').innerHTML += `                            <button id="categoria" class="w-1/2 px-4 py-2 text-sm font-semibold uppercase leading-5 text-center text-white transition-colors duration-150 bg-emerald-500 border border-transparent rounded-lg active:bg-emerald-600 hover:bg-emerald-700 focus:outline-none">Nueva categoría<i class="fas fa-archive ml-3"></i></button>`;
        const newCat = document.querySelector('#categoria');

        newCat.addEventListener('click', evt => {
            modal.classList.toggle('hidden');

            guardar.addEventListener('click', crear);

            closeModal.forEach( e =>{
                e.addEventListener('click', cerrarCrear);
            });

            function cerrarCrear(e){
                modal.classList.toggle('hidden');
                guardar.removeEventListener('click', crear);
                nombreCategoria.value = '';
                precioCategoria.value = '';
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
                        solicitud: "c_c",
                        name: nombreCategoria.value,
                        price: precioCategoria.value,
                    },
                    success: function(data) {
                        nombreCategoria.value = '';
                        precioCategoria.value = '';
                        tablaCategorias.ajax.reload();
                        guardar.removeEventListener('click', crear);
                        closeModal.forEach( e =>{
                            e.removeEventListener('click', cerrarCrear);
                        });
                        Toastify({
                            text: "Categoría agregada",
                            duration: 3000,
                            style: {
                                background: '#10B981'
                            }
                        }).showToast();
                    }
                });
            }

        });
    },
    columns: [
        { "data": "id" },
        { "data": "name" },
        { "data": "price" },
        {
            "data": null,
            render:function(data, type, row)
            {
                if(data['status']){
                    return '<button value="'+data['id']+'" type="button" name="desactivar" class="flex items-center justify-between text-2xl px-1 font-medium leading-5 text-emerald-500 rounded-lg focus:outline-none focus:shadow-outline-gray .btn-borrar" onclick="interruptor(this)"><i class="fas fa-toggle-on"></i></button>';
                }
                else{
                    return '<button value="'+data['id']+'" type="button" name="activar" class="flex items-center justify-between text-2xl font-medium px-1 leading-5 text-red-500 rounded-lg focus:outline-none focus:shadow-outline-gray .btn-borrar" onclick="interruptor(this)"><i class="fas fa-toggle-off"></i></button>';
                }
            },
            "targets": -1
        },
        {
            "data": null,
            render:function(data, type, row)
            {
                return '<button value="'+data['id']+'" type="button" class="flex items-center justify-between px-2 py-2 text-lg font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray btn-editar" onclick="editar(this)"><i class="fas fa-edit"></i></i></button>';
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

const closeModal = document.querySelectorAll('.close'),
    modal = document.querySelector('#modal'),
    titulo_modal = document.querySelector('#titulo-modal'),
    guardar = document.querySelector('button[name="guardar"]'),
    nombreCategoria = document.querySelector('input[name="name"]'),
    precioCategoria= document.querySelector('input[name="price"]');

function editar(e){

    titulo_modal.textContent = 'Editar categoría';
    guardar.textContent = 'Actualizar';

    $.ajax({
        url: "./functions.php",
        type: "POST",
        datatype:"json",
        data:  {
            solicitud: "id",
            id: e.value,
        },
        success: function(data) {

            nombreCategoria.value = data['name'];
            precioCategoria.value = data['price'];

            modal.classList.toggle('hidden');
            guardar.addEventListener('click', actualizar);

            closeModal.forEach( e =>{
                e.addEventListener('click', cerrarActualizar);
            });

            function cerrarActualizar(e){
                modal.classList.toggle('hidden');
                guardar.removeEventListener('click', actualizar);
                nombreCategoria.value = '';
                precioCategoria.value = '';
                closeModal.forEach( e =>{
                    e.removeEventListener('click', cerrarActualizar);
                });
                titulo_modal.textContent = 'Nueva categoría';
                guardar.textContent = 'Guardar';
            }

            function actualizar(evt) {

                $.ajax({
                    url: "./functions.php",
                    type: "POST",
                    datatype:"json",
                    data:  {
                        solicitud: "u",
                        name: nombreCategoria.value,
                        price: precioCategoria.value,
                        id: e.value
                    },
                    success: function(data) {
                        nombreCategoria.value = '';
                        precioCategoria.value = '';
                        tablaCategorias.ajax.reload();
                        titulo_modal.textContent = 'Nueva evento';
                        guardar.textContent = 'Guardar';
                        guardar.removeEventListener('click', actualizar);
                        closeModal.forEach( e =>{
                            e.removeEventListener('click', cerrarActualizar);
                        });
                        Toastify({
                            text: "Categoría actualizada",
                            duration: 3000,
                            style: {
                                background: '#10B981'
                            }
                        }).showToast();
                        modal.classList.toggle('hidden');
                    }
                });
            }
        }
    });
}

function interruptor(e) {

    let estado = 0;

    if(e.name == "activar"){
        estado = 1;
    }

    $.ajax({
        url: "./functions.php",
        type: "POST",
        datatype:"json",
        data:  {
            solicitud: "d",
            id: e.value,
            status: estado
        },
        success: function(data) {
            tablaCategorias.ajax.reload();
            if(estado){
                Toastify({
                    text: "Categoría activada!",
                    duration: 3000,
                    style: {
                        background: '#10B981'
                    }
                }).showToast();
            }
            else{
                Toastify({
                    text: "Categoría desactivada!",
                    duration: 3000,
                    style: {
                        background: '#EF4444'
                    }
                }).showToast();
            }
        }
    });
}