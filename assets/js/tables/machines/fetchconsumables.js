const urlParams = new URLSearchParams(window.location.search);
const area = urlParams.get('area');

tablaAreas = $('#datatable-json').DataTable({
    language: { url: "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json" },
    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": "./functions.php",
        "data": {
            "solicitud":"consumables",
            "area": area
        }
    },
    "dom": 'Brtip',
    "initComplete":function( settings, json){
        document.querySelector('.dt-buttons').innerHTML += `<button id="area" class="w-1/2 px-4 py-2 text-sm font-semibold uppercase leading-5 text-center text-white transition-colors duration-150 bg-emerald-500 border border-transparent rounded-lg active:bg-emerald-600 hover:bg-emerald-700 focus:outline-none">Agregar insumo<i class="fas fa-archive ml-3"></i></button>`;
        const newArea = document.querySelector('#area');

        newArea.addEventListener('click', evt =>{
            feedbackname.textContent = ''
            nombreArea.value = '';
            unidadArea.options[0].selected = true;

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
                if(data[3]){
                    return '<button value="'+data[0]+'" type="button" name="desactivar" class="flex items-center justify-between text-2xl px-1 font-medium leading-5 text-emerald-500 rounded-lg focus:outline-none focus:shadow-outline-gray" onclick="interruptor(this)"><i class="fas fa-toggle-on"></i></button>';
                }
                else{
                    return '<button value="'+data[0]+'" type="button" name="activar" class="flex items-center justify-between text-2xl font-medium px-1 leading-5 text-red-500 rounded-lg focus:outline-none focus:shadow-outline-gray" onclick="interruptor(this)"><i class="fas fa-toggle-off"></i></button>';
                }
            },
            "targets": 4
        },
        {
            "data": null,
            render:function(data, type, row)
            {
                return '<button value="'+data[0]+'" type="button" class="flex items-center justify-between px-2 py-2 text-lg font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray" onclick="editar(this)"><i class="fas fa-edit"></i></button>';
            },
            "targets": 5
        },
        { 'visible': false, 'targets': [0] }
    ]
});