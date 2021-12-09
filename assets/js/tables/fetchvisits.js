var posting = $.post( './functions.php', { solicitud: 'ar' } );

let areas = [];
let reasonVisits = [];

posting.done(function( data ) {
    areas = data;
});

posting = $.post( './functions.php', { solicitud: 'raz' } );

posting.done(function( data ) {
    reasonVisits = data;
});

tablaVisitas = $('#datatable-json').DataTable({
    language: { url: "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json" },
    ajax:{
        url: './functions.php',
        type: 'POST',
        data: {solicitud:'v'},
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
        { "data": "visit_id" },
        { "data": "customer_id" },
        { "data": "reason_id" },
        {
            "data": null,
            render:function(data, type, row)
            {
                if(data['time']){
                    return '<button value="'+data['visit_id']+'" onclick="editarAreas(this)"><span class="inline-flex px-2 text-xs font-medium leading-5 rounded-full text-blue-700 bg-blue-100">Ver areas</span></button>';
                }
                else{
                    return '<button><span class="inline-flex px-2 text-xs font-medium leading-5 rounded-full text-yellow-700 bg-yellow-100">Sin areas</span></button>';
                }
            },
            "targets": -1
        },
        { "data": "date" },
        {
            "data": null,
            render:function(data, type, row)
            {
                if(data['observation'] == null){
                    return 'Sin observación';
                }
                else{
                    return data['observation'];
                }
            },
            "targets": -1
        },
        {
            "data": null,
            render:function(data, type, row)
            {
                if(data['status'] == 1){
                    return '<span class="inline-flex px-2 text-xs font-medium leading-5 rounded-full text-green-700 bg-green-100">Activo</span>';
                }
                else{
                    return '<span class="inline-flex px-2 text-xs font-medium leading-5 rounded-full text-red-700 bg-red-100">Inactivo</span>';
                }
            },
            "targets": -1
        },
        {
            "data": null,
            render:function(data, type, row)
            {
                return '<div class="flex items-center space-x-4"> <button value="'+data['visit_id']+'" type="button" class="flex items-center justify-between px-2 py-2 text-base font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray btn-editar" onclick="editarVisita(this)"><i class="fas fa-edit"></i></i></button><button value="'+data['visit_id']+'" type="button" name="borrar" class="flex items-center justify-between px-2 py-2 text-base font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray .btn-borrar" onclick="borrar(this)"><i class="fas fa-trash-alt"></i></button></div>';
            },
            "targets": -1
        }
    ],
    responsive: true,
    processing: true,
    'columnDefs' : [
        //hide the second & fourth column
        { 'visible': false, 'targets': [0,5,6] }
    ],
    order: [[ 0, "desc" ]]
});

const modal_content = document.querySelector('#modal-content'),
    closeModal = document.querySelectorAll('.close'),
    guardar = document.querySelector('button[name="guardar"]'),
    modal = document.querySelector('#modal');

function editarAreas(e){

    modal_content.innerHTML = `
                    <div class="text-sm" id="containerarea">
                            <span class="text-gray-800">Áreas de trabajo seleccionadas</span></div>`;

    $.ajax({
        url: "./functions.php",
        type: "POST",
        datatype:"json",
        data:  {
            solicitud: "id_va",
            id: e.value,
        },
        success: function(data) {
            areas.forEach(v =>{
                document.querySelector('#containerarea').innerHTML += `
       <label class="flex items-center mt-4">
                                    <input type="checkbox" value="${v.id}" name="areacheck${v.id}" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2">${v.name}</span>
                            </label>

                            <div class="p-3 hidden" id="area${v.id}">
                                    <label for="arrival_time" class="mr-6">Hora de llegada:
                                        <input type="time" name="arrival_time_area${v.id}" class="text-sm p-1.5 m-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    </label>

                                    <label for="departure_time">Hora de salida:
                                        <input type="time" name="departure_time_area${v.id}" class="text-sm p-1.5 m-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    </label>
                                </div>
              `;
            });

            let areasTrabajo = document.querySelectorAll('input[type="checkbox"]');

            areasTrabajo.forEach(evt =>{
                evt.addEventListener('click', item =>{
                    const areaCheck = document.querySelector('#area'+item.target.value);
                    if(areaCheck.nextElementSibling){areaCheck.nextElementSibling.classList.toggle('mt-4');}
                    areaCheck.classList.toggle('hidden');
                });
            });

            data.forEach(x => {
                document.querySelector('input[name="'+'areacheck'+x.area_id+'"]').checked = true;
                const areaCheck = document.querySelector('#area'+x.area_id);
                if(areaCheck.nextElementSibling){areaCheck.nextElementSibling.classList.toggle('mt-4');}
                document.querySelector('#area'+x.area_id).classList.toggle('hidden');
                document.querySelector('input[name="'+'arrival_time_area'+x.area_id+'"]').value = x.arrival_time;
                document.querySelector('input[name="'+'departure_time_area'+x.area_id+'"]').value = x.departure_time;
            });

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
            }

            function actualizar(evt) {
                let areasActualizar = [];

                areasTrabajo.forEach(x =>{
                    if(x.checked){
                        let areaCheck = {
                            id: x.value,
                            arrival_time: document.querySelector('input[name="'+'arrival_time_area'+x.value+'"]').value,
                            departure_time: document.querySelector('input[name="'+'departure_time_area'+x.value+'"]').value
                        }

                        if(areaCheck.arrival_time.trim().length == 0){
                            //Validar
                        }
                        areasActualizar.push(areaCheck);
                    }
                });

                if(areas.length == 0){
                    //Validar
                }

                $.ajax({
                    url: "./functions.php",
                    type: "POST",
                    datatype:"json",
                    data:  {
                        solicitud: "up_va",
                        areas: areasActualizar,
                        visita: e.value
                    },
                    success: function(data) {
                        modal.classList.toggle('hidden');
                        tablaVisitas.ajax.reload();
                        guardar.removeEventListener('click', actualizar);
                        closeModal.forEach( e =>{
                            e.removeEventListener('click', cerrarActualizar);
                        });
                        Toastify({
                            text: "Areas Actualizadas",
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

function editarVisita(e){

    $.ajax({
        url: "./functions.php",
        type: "POST",
        datatype:"json",
        data:  {
            solicitud: "id_v",
            id: e.value,
        },
        success: function(data) {
            modal_content.innerHTML = `                                
                                <label class="block text-sm">
                                    <span class="text-gray-800">Seleccione el tipo de documento</span>
                            <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="tipodocumento">
                                <option value="R">RUC</option>
                                <option value="C">Cédula</option>
                                <option value="P">Pasaporte</option>
                            </select>
                                </label>
                                
                                <label class="block text-sm mt-5">
                                    <span class="text-gray-800" id="tituloDocumento">Numero de RUC de visitante</span>
                                    <input class="text-sm mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="text" required="" placeholder="Ingrese el número de RUC con guiones" name="documento" id="autoComplete" autocomplete="false">
                                    <input type="hidden" name="id_customer">
                                </label>
                                
                                <label class="block text-sm mt-5">
                                    <span class="text-gray-800">Nombre de visitante</span>
                                    <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" placeholder="Sin visitante seleccionado" type="text" name="name" disabled>
                                </label>

                                <label class="block text-sm mt-5">
                                    <span class="text-gray-800">Seleccione la razón de visita</span>
                                    <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="razonvisita">
                            </select>
                                </label>                                
                                
                                <label class="block text-sm mt-5">
                                    <span class="text-gray-800">Seleccione la fecha de la visita</span>
                                    <input class="text-sm mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="date" name="fecha">
                                </label>

                                <label class="block text-sm mt-5">
                                    <span class="text-gray-800">Observación complementaria</span>
                                    <textarea class="text-sm mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Sin observación complementaria" name="observacion"></textarea>
                                </label>`;

            const tipoDocumento = document.querySelector('select[name="tipodocumento"]'),
                tituloDocumento=  document.querySelector('#tituloDocumento'),
                inputDocumento = document.querySelector('input[name="documento"]'),
                nombreCliente = document.querySelector('input[name="name"]'),
                fechaVisita = document.querySelector('input[name="fecha"]'),
                razonVisita = document.querySelector('select[name="razonvisita"]'),
                observacion = document.querySelector('textarea[name="observacion"]'),
                idHidden = document.querySelector('input[type="hidden"]');

            const TIPOS_DOCUMENTOS = {
                C: () => {
                    tituloDocumento.textContent = 'Número de cédula de visitante';
                    inputDocumento.placeholder = "Ingrese el número de cédula con guiones";
                },
                R: () => {
                    tituloDocumento.textContent = 'Número de RUC de visitante';
                    inputDocumento.placeholder = "Ingrese el número de RUC con guiones";
                },
                P: () => {
                    tituloDocumento.textContent = 'Número de Pasaporte de visitante';
                    inputDocumento.placeholder = "Ingrese el número de pasaporte con guiones";
                }
            }

            inputDocumento.addEventListener('keyup', evt => {
                if(evt.key != "Enter"){
                    idHidden.value = '';
                    nombreCliente.value = '';
                }
            });

            tipoDocumento.addEventListener('change', evt => {
                TIPOS_DOCUMENTOS[evt.target.value]();
                inputDocumento.value = '';
                triggerKeyup(inputDocumento);
            });

            Array.from(tipoDocumento.options).forEach(v => {
                if(v.value == data['document_type']){
                    v.selected = true;
                    triggerChange(tipoDocumento);
                }
            })

            inputDocumento.value = data['document'];
            idHidden.value = data['customer_id'];
            nombreCliente.value = data['name'];

            $("#autoComplete").autocomplete({

                source: function (request, response) {
                    $.ajax({
                        url: "../../ajax.php",
                        type: 'post',
                        dataType: "json",
                        data: {
                            customers: request.term,
                            document_type: tipoDocumento.value
                        },
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                select: function (event, ui) {
                    $('#autoComplete').val(ui.item.label);
                    idHidden.value = ui.item.id;
                    nombreCliente.value = ui.item.name;


                    Toastify({
                        text: "Visitante seleccionado",
                        duration: 3000,
                        style: {
                            background: '#10B981'
                        }
                    }).showToast();

                    return false;
                }

            });

            function triggerKeyup(element){
                let keyupEvent = new Event('keyup');
                element.dispatchEvent(keyupEvent);
            }

            function triggerChange(element){
                let changeEvent = new Event('change');
                element.dispatchEvent(changeEvent);
            }

            reasonVisits.forEach( value => {
                razonVisita.innerHTML += `
<option value="${value.reason_id}" class="${value.time == 1  ? 'notfree' : 'free' }" ${value.reason_id == data['reason_id'] ? 'selected' : ''} >${value.name}</option>`;
            });

            fechaVisita.value = data['date'];
            observacion.value = data['observation'];

            modal.classList.toggle('hidden');
            guardar.addEventListener('click', actualizar);
            closeModal.forEach( e =>{
                e.addEventListener('click', cerrarActualizar);
            });

            function cerrarActualizar(e){
                modal.classList.toggle('hidden');
                guardar.removeEventListener('click', actualizar);
                closeModal.forEach( evt =>{
                    evt.removeEventListener('click', cerrarActualizar);
                });
            }

            function actualizar(evt) {
                $.ajax({
                    url: "./functions.php",
                    type: "POST",
                    datatype:"json",
                    data:  {
                        solicitud: "up_v",
                        customer_id: idHidden.value,
                        reason_id: razonVisita.value,
                        time: razonVisita.options[razonVisita.selectedIndex].classList.contains('free'),
                        date: fechaVisita.value,
                        observation: observacion.value,
                        visit_id: e.value
                    },
                    success: function(data) {
                        modal.classList.toggle('hidden');
                        tablaVisitas.ajax.reload();
                        guardar.removeEventListener('click', actualizar);
                        closeModal.forEach( e =>{
                            e.removeEventListener('click', cerrarActualizar);
                        });
                        Toastify({
                            text: "Areas Actualizadas",
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
        text: "La visita será desactivada.",
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
                    tablaVisitas.ajax.reload();
                    Toastify({
                        text: "Visita desactivada!",
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