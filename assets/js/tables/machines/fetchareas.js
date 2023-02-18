const TIPO_INSUMOS = {
    'Electrónica': './components.php',
    'Mini Fresadora CNC': './milling.php',
    'Láser CNC': './laser.php',
    'Cortadora de Vinilo': './vinyls.php',
    'Impresión 3D en filamento': './filaments.php',
    'Impresión 3D en resina': './resins.php',
    'Software de diseño': './software.php',
    'Bordadora CNC': './threads.php',
}

tablaAreas = $('#datatable-json').DataTable({
    language: { url: "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json" },
    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": "./functions.php",
        "data": {
            "solicitud":"areas",
        }
    },
    "dom": 'Brtip',
    "initComplete":function( settings, json){
        document.querySelector('.dt-buttons').innerHTML += `<button id="area" class="w-1/2 px-4 py-2 text-sm font-semibold uppercase leading-5 text-center text-white transition-colors duration-150 bg-emerald-500 border border-transparent rounded-lg active:bg-emerald-600 hover:bg-emerald-700 focus:outline-none">Agregar área<i class="fas fa-archive ml-3"></i></button>`;
        const newArea = document.querySelector('#area');

        newArea.addEventListener('click', evt =>{
            feedbackname.textContent = ''
            nombreArea.value = '';

            modal.classList.toggle('hidden');
            guardar.addEventListener('click', crear);
            closeModal.forEach( e =>{
                e.addEventListener('click', cerrarCrear);
            });

            function cerrarCrear(e){
                modal.classList.toggle('hidden');
                guardar.removeEventListener('click', crear);
                closeModal.forEach( e =>{
                    e.removeEventListener('click', cerrarCrear);
                });
            }

            function crear(e) {
                let errores = {};

                if(nombreArea.value.trim().length == 0){
                    errores.nombre = "Por favor, ingrese el nombre del área";
                    feedbackname.textContent = errores.nombre;
                    nombreArea.addEventListener('change', cambioValor);
                }

                if(Object.keys(errores).length == 0){
                    $.ajax({
                        url: "./functions.php",
                        type: "POST",
                        datatype:"json",
                        data:  {
                            solicitud: "c",
                            name: nombreArea.value,
                            measure: unidadArea.value
                        },
                        success: function(data) {
                            modal.classList.toggle('hidden');
                            tablaAreas.ajax.reload();
                            guardar.removeEventListener('click', crear);
                            closeModal.forEach( e =>{
                                e.removeEventListener('click', cerrarCrear);
                            });
                            Toastify({
                                text: "Área agregada",
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
    },
    "columnDefs": [
        {
            "data": null,
            render:function(data, type, row)
            {
                if(data[2] == 1){
                    return '<button value="'+data[0]+'" type="button" name="desactivar" class="flex items-center justify-between text-2xl px-1 font-medium leading-5 text-emerald-500 rounded-lg focus:outline-none focus:shadow-outline-gray" onclick="interruptor(this)"><i class="fas fa-toggle-on"></i></button>';
                }
                else{
                    return '<button value="'+data[0]+'" type="button" name="activar" class="flex items-center justify-between text-2xl font-medium px-1 leading-5 text-red-500 rounded-lg focus:outline-none focus:shadow-outline-gray" onclick="interruptor(this)"><i class="fas fa-toggle-off"></i></button>';
                }
            },
            "targets": 2
        },
        {
            "data": null,
            render:function(data, type, row)
            {
                return '<div class="flex items-center space-x-4"><a href="'+TIPO_INSUMOS[data[1]]+'" class="flex items-center px-2 py-2 text-lg font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray"><i class="fas fa-boxes"></i></a> <button value="'+data[0]+'" type="button" class="flex items-center justify-between px-2 py-2 text-lg font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray" onclick="editar(this)"><i class="fas fa-edit"></i></button></div>';
            },
            "targets": 3
        },
        { 'visible': false, 'targets': [0] }
    ]
});

const closeModal = document.querySelectorAll('.close'),
    modal = document.querySelector('#modal'),
    titulo_modal = document.querySelector('#titulo-modal'),
    guardar = document.querySelector('button[name="guardar"]'),
    nombreArea = document.querySelector('input[name="name"]'),
    feeds = document.querySelectorAll('.feed');

function cambioValor(e) {
    e.target.nextElementSibling.textContent = '';
    e.target.removeEventListener('change',cambioValor);
}

function editar(e){
    feedbackname.textContent ='';

    titulo_modal.textContent = 'Editar área';
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
            console.log(data);
            nombreArea.value = data['name'];

            modal.classList.toggle('hidden');
            guardar.addEventListener('click', actualizar);

            closeModal.forEach( e =>{
                e.addEventListener('click', cerrarActualizar);
            });

            function cerrarActualizar(e){
                modal.classList.toggle('hidden');
                guardar.removeEventListener('click', actualizar);
                closeModal.forEach( e =>{
                    e.removeEventListener('click', cerrarActualizar);
                });
                titulo_modal.textContent = 'Nueva área';
                guardar.textContent = 'Guardar';
            }

            function actualizar(evt) {

                let errores = {};

                if(nombreArea.value.trim().length == 0){
                    errores.nombre = "Por favor, ingrese el nombre del área";
                    feedbackname.textContent = errores.nombre;
                    nombreArea.addEventListener('change', cambioValor);
                }

                if(Object.keys(errores).length == 0){
                    $.ajax({
                        url: "./functions.php",
                        type: "POST",
                        datatype:"json",
                        data:  {
                            solicitud: "u",
                            name: nombreArea.value,
                            id: e.value
                        },
                        success: function(data) {
                            tablaAreas.ajax.reload();
                            titulo_modal.textContent = 'Nueva área';
                            guardar.textContent = 'Guardar';
                            guardar.removeEventListener('click', actualizar);
                            closeModal.forEach( e =>{
                                e.removeEventListener('click', cerrarActualizar);
                            });
                            Toastify({
                                text: "Área actualizada",
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
            tablaAreas.ajax.reload();
            if(estado){
                Toastify({
                    text: "Área activada!",
                    duration: 3000,
                    style: {
                        background: '#10B981'
                    }
                }).showToast();
            }
            else{
                Toastify({
                    text: "Área desactivada!",
                    duration: 3000,
                    style: {
                        background: '#EF4444'
                    }
                }).showToast();
            }
        }
    });
}