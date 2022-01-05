// Send the data using post
var posting = $.post( './functions.php', { solicitud: 'c', status: 1 } );

let categoriasEventos = [];

// Put the results in a div
posting.done(function( data ) {
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

tablaEventos = $('#datatable-json').DataTable({
    language: { url: "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json" },
    ajax:{
        url: './functions.php',
        type: 'POST',
        data: {solicitud:'e'},
        dataSrc:""
    },
    dom: 'Bfrtip',
    buttons: [
        {
            extend: 'colvis',
            columns: ':not(".select-disabled")'
        }
    ],
    columns: [
        { "data": "event_id" },
        { "data": "category_id" },
        { "data": "name" },
        { "data": "initial_date" },
        { "data": "final_date" },
        { "data": "number_hours" },
        { "data": "price" },
        {
            "data": null,
            render:function(data, type, row)
            {

                if(data['expenses']){
                    return data['expenses'];
                }
                else{
                    return '0.00';
                }
            },
            "targets": -1
        },
        {
            "data": null,
            render:function(data, type, row)
            {
                if(data['description_expenses'] == null){
                    return 'Sin descripción';
                }
                else{
                    return data['description_expenses'];
                }
            },
            "targets": -1
        },
        {
            "data": null,
            render:function(data, type, row)
            {
                if(data['status']){
                    return '<button value="'+data['event_id']+'" type="button" name="desactivar" class="flex items-center justify-between text-2xl px-1 font-medium leading-5 text-emerald-500 rounded-lg focus:outline-none focus:shadow-outline-gray .btn-borrar" onclick="interruptor(this)"><i class="fas fa-toggle-on"></i></button>';
                }
                else{
                    return '<button value="'+data['event_id']+'" type="button" name="activar" class="flex items-center justify-between text-2xl font-medium px-1 leading-5 text-red-500 rounded-lg focus:outline-none focus:shadow-outline-gray .btn-borrar" onclick="interruptor(this)"><i class="fas fa-toggle-off"></i></button>';
                }
            },
            "targets": -1
        },
        {
            "data": null,
            render:function(data, type, row)
            {
                return '<button value="'+data['event_id']+'" type="button" class="flex items-center justify-between px-2 py-2 text-lg font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray btn-editar" onclick="editar(this)"><i class="fas fa-edit"></i></i></button>';
            },
            "targets": -1
        }

    ],
    responsive: true,
    processing: true,
    'columnDefs' : [
        //hide the second & fourth column
        { 'visible': false, 'targets': [0,5,6,7,8] }
    ],
    order: [[ 0, "desc" ]]
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
    nombreEvento = document.querySelector('input[name="nombre_evento"]'),
    cantidadHoras = document.querySelector('input[name="cantidad_horas"]'),
    fechaInicial = document.querySelector('input[name="fecha_inicial"]'),
    fechaFinal = document.querySelector('input[name="fecha_final"]'),
    precioEvento = document.querySelector('input[name="precio"]'),
    gastosEvento = document.querySelector('input[name="gastos_evento"]'),
    descripcionGastos = document.querySelector('textarea[name="desc_gastos"]');

categoria.addEventListener('change', evt => {

    if(evt.target.value){
        const respuestaEvento = categoriasEventos.find(x => x.id == evt.target.value);
        precioEvento.value = respuestaEvento.price;
    }

});

newEvent.addEventListener('click', evt => {
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
        nombreEvento.value = '';
        cantidadHoras.value = '';
        fechaInicial.value = '';
        fechaFinal.value = '';
        gastosEvento.value = '';
        descripcionGastos.value = '';
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
                solicitud: "c_e",
                categoria: categoria.value,
                nombre: nombreEvento.value,
                horas: cantidadHoras.value,
                inicial: fechaInicial.value,
                final: fechaFinal.value,
                precio: precioEvento.value,
                gastos: gastosEvento.value,
                descripcion: descripcionGastos.value
            },
            success: function(data) {
                categoria.options[0].selected = true;
                nombreEvento.value = '';
                cantidadHoras.value = '';
                fechaInicial.value = '';
                fechaFinal.value = '';
                gastosEvento.value = '';
                descripcionGastos.value = '';
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
            }
        });
    }

});

function editar(e){

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

            Array.from(categoria.options).forEach( (opt) =>{
                if(opt.value == data['category_id']){
                    opt.selected = true;
                }
            });

            nombreEvento.value = data['name'];
            fechaInicial.value = data['initial_date'];
            fechaFinal.value = data['final_date'];
            cantidadHoras.value = data['number_hours'];
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
                nombreEvento.value = '';
                cantidadHoras.value = '';
                fechaInicial.value = '';
                fechaFinal.value = '';
                gastosEvento.value = '';
                descripcionGastos.value = '';
                closeModal.forEach( e =>{
                    e.removeEventListener('click', cerrarActualizar);
                });
                titulo_modal.textContent = 'Nuevo evento';
                guardar.textContent = 'Guardar';
            }

            function actualizar(evt) {

                $.ajax({
                    url: "./functions.php",
                    type: "POST",
                    datatype:"json",
                    data:  {
                        solicitud: "u_e",
                        categoria: categoria.value,
                        nombre: nombreEvento.value,
                        horas: cantidadHoras.value,
                        inicial: fechaInicial.value,
                        final: fechaFinal.value,
                        precio: precioEvento.value,
                        gastos: gastosEvento.value,
                        descripcion: descripcionGastos.value,
                        id: e.value
                    },
                    success: function(data) {
                        categoria.options[0].selected = true;
                        nombreEvento.value = '';
                        cantidadHoras.value = '';
                        fechaInicial.value = '';
                        fechaFinal.value = '';
                        gastosEvento.value = '';
                        descripcionGastos.value = '';
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