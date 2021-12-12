// Send the data using post
var posting = $.post( './functions.php', { solicitud: 'cat' } );

let categoriasEventos = [];

// Put the results in a div
posting.done(function( data ) {
    categoriasEventos = data;
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
                if(data['status'] == 1){
                    return '<span class="inline-flex px-2 text-xs font-medium leading-5 rounded-full text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100">Activo</span>';
                }
                else{
                    return '<span class="inline-flex px-2 text-xs font-medium leading-5 rounded-full text-red-700 bg-red-100 dark:text-red-100 dark:bg-red-700">Inactivo</span>';
                }
            },
            "targets": -1
        },
        {
            "data": null,
            render:function(data, type, row)
            {
                return '<div class="flex items-center space-x-4"> <button value="'+data['event_id']+'" type="button" class="flex items-center justify-between px-2 py-2 text-base font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray btn-editar" onclick="editar(this)"><i class="fas fa-edit"></i></i></button><button value="'+data['event_id']+'" type="button" name="borrar" class="flex items-center justify-between px-2 py-2 text-base font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray .btn-borrar" onclick="borrar(this)"><i class="fas fa-trash-alt"></i></button></div>';
            },
            "targets": -1
        }

    ],
    responsive: true,
    processing: true,
    'columnDefs' : [
        //hide the second & fourth column
        { 'visible': false, 'targets': [0,5,6,7,8,9] }
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
    const respuestaEvento = categoriasEventos.find(x => x.id == evt.target.value);
    precioEvento.value = respuestaEvento.price;
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
                solicitud: "c",
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
            solicitud: "id",
            id: e.value,
        },
        success: function(data) {

            Array.from(categoria.options).forEach( (opt) =>{
                if(opt.value = data['category_id']){
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
                modal.classList.toggle('hidden');

                $.ajax({
                    url: "./functions.php",
                    type: "POST",
                    datatype:"json",
                    data:  {
                        solicitud: "u",
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
                    }
                });
            }
        }
    });
}

function borrar(e) {

    Swal.fire({
        title: '¿Estás seguro?',
        text: "El evento será desactivado.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#10B981',
        cancelButtonColor: '#EF4444',
        confirmButtonText: 'Si, desactivar ahora!',
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
                    tablaEventos.ajax.reload();
                    Toastify({
                        text: "Evento desactivado!",
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