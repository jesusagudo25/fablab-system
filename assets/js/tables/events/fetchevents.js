// Send the data using post
var gesting = $.get( './functions.php', { solicitud: 'c', status: 1 } );

let categoriasEventos = [];

// Put the results in a div
gesting.done(function( data ) {
    categoriasEventos = data;
    if(data.length){
        data.forEach(v => {
            categoria.innerHTML += `<option value="${v.id}">${v.name}</option>`;
        })
    }
    else{
        categoria.innerHTML += `<option value="">Sin categorías disponibles</option>`;
    }
});

// Send the data using post
var posting = $.post( './functions.php', { solicitud: 'a' } );

// Put the results in a div
posting.done(function( data ) {
    if(data.length){
        data.forEach(x => {
            area.innerHTML += `<option value="${x.id}">${x.name}</option>`;
        })
    }
    else{
        area.innerHTML += `<option value="">Sin áreas disponibles</option>`;
    }
});

tablaEventos = $('#datatable-json').DataTable({
    language: { url: "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json" },
    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": "./functions.php",
        "data": {
            "solicitud":"e"
        }
    },
    "columnDefs": [
        {
            "data": null,
            render:function(data, type, row)
            {
                if(data[5]){
                    return '<button value="'+data[0]+'" type="button" name="desactivar" class="flex items-center justify-between text-2xl px-1 font-medium leading-5 text-emerald-500 rounded-lg focus:outline-none focus:shadow-outline-gray .btn-borrar" onclick="interruptor(this)"><i class="fas fa-toggle-on"></i></button>';
                }
                else{
                    return '<button value="'+data[0]+'" type="button" name="activar" class="flex items-center justify-between text-2xl font-medium px-1 leading-5 text-red-500 rounded-lg focus:outline-none focus:shadow-outline-gray .btn-borrar" onclick="interruptor(this)"><i class="fas fa-toggle-off"></i></button>';
                }
            },
            "targets": 5
        },
        {
            "data": null,
            render:function(data, type, row)
            {
                return '<button value="'+data[0]+'" type="button" class="flex items-center justify-between px-2 py-2 text-lg font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray btn-editar" onclick="editar(this)"><i class="fas fa-edit"></i></i></button>';
            },
            "targets": 6
        },
        { 'visible': false, 'targets': [0] }
    ],
    "order": [[ 0, "desc" ]]
});

function triggerChange(element){
    let changeEvent = new Event('change');
    element.dispatchEvent(changeEvent);
}

const newEvent = document.querySelector('#evento'),
    titulo_modal = document.querySelector('#titulo-modal'),
    closeModal = document.querySelectorAll('.close'),
    modal = document.querySelector('#modal'),
    guardar = document.querySelector('button[name="guardar"]'),
    categoria = document.querySelector('select[name="categoria"]'),
    area = document.querySelector('select[name="area"]'),
    nombreEvento = document.querySelector('input[name="nombre_evento"]'),
    horaInicial = document.querySelector('input[name="hora_inicial"]'),
    horaFinal = document.querySelector('input[name="hora_final"]'),
    fechaInicial = document.querySelector('input[name="fecha_inicial"]'),
    fechaFinal = document.querySelector('input[name="fecha_final"]'),
    precioEvento = document.querySelector('input[name="precio"]'),
    gastosEvento = document.querySelector('input[name="gastos_evento"]'),
    descripcionGastos = document.querySelector('textarea[name="desc_gastos"]'),
    inputs = document.querySelectorAll('#modal input, #modal textarea'),
    feeds = document.querySelectorAll('.feed');


inputs.forEach( x =>{
    x.addEventListener('change', evt =>{
        evt.target.nextElementSibling.textContent = '';
    })
});

categoria.addEventListener('change', evt => {
    if(evt.target.value){
        const respuestaEvento = categoriasEventos.find(x => x.id == evt.target.value);
        precioEvento.value = respuestaEvento.price;
        feedbackprecio.textContent = '';
    }
});

newEvent.addEventListener('click', evt => {

    feeds.forEach(x =>{
        x.textContent = '';
    });

    inputs.forEach(x =>{
        x.value = '';
    });

    modal.classList.toggle('hidden');
    triggerChange(categoria)
    guardar.addEventListener('click', crear);

    closeModal.forEach( e =>{
        e.addEventListener('click', cerrarCrear);
    });

    function cerrarCrear(e){
        modal.classList.toggle('hidden');
        guardar.removeEventListener('click', crear);
        categoria.options[0].selected = true;
        closeModal.forEach( e =>{
            e.removeEventListener('click', cerrarCrear);
        });
    }

    function crear(e) {
        let errores = {};

        if(nombreEvento.value.trim().length == 0){
            errores.nombre = "Por favor, ingrese el nombre del evento";
            feedbacknombre.textContent = errores.nombre;
        }

        if(precioEvento.value.trim().length == 0){
            errores.precio = "Por favor, ingrese el precio del evento";
            feedbackprecio.textContent = errores.precio;
        }

        if(horaInicial.value.trim().length == 0){
            errores.horainicial = "Por favor, seleccione la hora inicial del evento";
            feedbackstarttime.textContent = errores.horainicial;
        }

        if(horaFinal.value.trim().length == 0){
            errores.horafinal = "Por favor, seleccione la hora final del evento";
            feedbackendtime.textContent = errores.horafinal;
        }

        if(fechaInicial.value.trim().length == 0){
            errores.fechainicial = "Por favor, seleccione la fecha inicial del evento";
            feedbackinitialdate.textContent = errores.fechainicial;
        }

        if(fechaFinal.value.trim().length == 0){
            errores.fechafinal = "Por favor, seleccione la fecha final del evento";
            feedbackfinaldate.textContent = errores.fechafinal;
        }

        if(gastosEvento.value.trim().length == 0){
            errores.gastos = "Por favor, ingrese el gasto total del evento";
            feedbackgastos.textContent = errores.gastos;
        }

        if(Object.keys(errores).length == 0){
            $.ajax({
                url: "./functions.php",
                type: "POST",
                datatype:"json",
                data:  {
                    solicitud: "c_e",
                    categoria: categoria.value,
                    area: area.value,
                    nombre: nombreEvento.value,
                    horainicial: horaInicial.value,
                    horafinal: horaFinal.value,
                    fechainicial: fechaInicial.value,
                    fechafinal: fechaFinal.value,
                    precio: precioEvento.value,
                    gastos: gastosEvento.value,
                    descripcion: descripcionGastos.value
                },
                success: function(data) {
                    categoria.options[0].selected = true;
                    area.options[0].selected = true;
                    tablaEventos.ajax.reload();
                    guardar.removeEventListener('click', crear);
                    closeModal.forEach( e =>{
                        e.removeEventListener('click', cerrarCrear);
                    });
                    Toastify({
                        text: "Evento agregado",
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

function editar(e){

    feeds.forEach(x =>{
        x.textContent = '';
    });

    titulo_modal.textContent = 'Editar evento';
    guardar.textContent = 'Actualizar';

    $.ajax({
        url: "./functions.php",
        type: "POST",
        datatype:"json",
        data:  {
            solicitud: "id_e",
            id: e.value,
        },
        success: function(data) {

            categoria.value = data['category_id']
            area.value = data['area_id'];
            nombreEvento.value = data['name'];
            horaInicial.value = data['start_time'];
            horaFinal.value = data['end_time'];
            fechaInicial.value = data['initial_date'];
            fechaFinal.value = data['final_date'];
            precioEvento.value = data['price'];
            gastosEvento.value = data['expenses'];
            descripcionGastos.value = data['description_expenses'];

            modal.classList.toggle('hidden');
            guardar.addEventListener('click', actualizar);

            closeModal.forEach( e =>{
                e.addEventListener('click', cerrarActualizar);
            });

            function cerrarActualizar(e){
                modal.classList.toggle('hidden');
                guardar.removeEventListener('click', actualizar);
                categoria.options[0].selected = true;
                closeModal.forEach( e =>{
                    e.removeEventListener('click', cerrarActualizar);
                });
                titulo_modal.textContent = 'Nuevo evento';
                guardar.textContent = 'Guardar';
            }

            function actualizar(evt) {

                let errores = {};

                if(nombreEvento.value.trim().length == 0){
                    errores.nombre = "Por favor, ingrese el nombre del evento";
                    feedbacknombre.textContent = errores.nombre;
                }
        
                if(precioEvento.value.trim().length == 0){
                    errores.precio = "Por favor, ingrese el precio del evento";
                    feedbackprecio.textContent = errores.precio;
                }

                if(horaInicial.value.trim().length == 0){
                    errores.horainicial = "Por favor, seleccione la hora inicial del evento";
                    feedbackstarttime.textContent = errores.horainicial;
                }
        
                if(horaFinal.value.trim().length == 0){
                    errores.horafinal = "Por favor, seleccione la hora final del evento";
                    feedbackendtime.textContent = errores.horafinal;
                }

                if(fechaInicial.value.trim().length == 0){
                    errores.fechainicial = "Por favor, seleccione la fecha inicial del evento";
                    feedbackinicial.textContent = errores.fechainicial;
                }
        
                if(fechaFinal.value.trim().length == 0){
                    errores.fechafinal = "Por favor, seleccione la fecha final del evento";
                    feedbackfinal.textContent = errores.fechafinal;
                }
        
                if(gastosEvento.value.trim().length == 0){
                    errores.gastos = "Por favor, ingrese el gasto total del evento";
                    feedbackgastos.textContent = errores.gastos;
                }
        
                if(Object.keys(errores).length == 0){
                    $.ajax({
                        url: "./functions.php",
                        type: "POST",
                        datatype:"json",
                        data:  {
                            solicitud: "u_e",
                            categoria: categoria.value,
                            area: area.value,
                            nombre: nombreEvento.value,
                            horainicial: horaInicial.value,
                            horafinal: horaFinal.value,
                            fechainicial: fechaInicial.value,
                            fechafinal: fechaFinal.value,
                            precio: precioEvento.value,
                            gastos: gastosEvento.value,
                            descripcion: descripcionGastos.value,
                            id: e.value
                        },
                        success: function(data) {
                            categoria.options[0].selected = true;
                            area.options[0].selected = true;
                            tablaEventos.ajax.reload();
                            titulo_modal.textContent = 'Nueva evento';
                            guardar.textContent = 'Guardar';
                            guardar.removeEventListener('click', actualizar);
                            closeModal.forEach( e =>{
                                e.removeEventListener('click', cerrarActualizar);
                            });
                            Toastify({
                                text: "Evento actualizado",
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
            solicitud: "d_e",
            id: e.value,
            status: estado
        },
        success: function(data) {
            tablaEventos.ajax.reload();
            if(estado){
                Toastify({
                    text: "Evento activado!",
                    duration: 3000,
                    style: {
                        background: '#10B981'
                    }
                }).showToast();
            }
            else{
                Toastify({
                    text: "Evento desactivado!",
                    duration: 3000,
                    style: {
                        background: '#EF4444'
                    }
                }).showToast();
            }
        }
    });
}