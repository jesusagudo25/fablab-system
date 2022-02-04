tablaAreas = $('#datatable-json').DataTable({
    language: { url: "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json" },
    ajax:{
        url: './functions.php',
        type: 'POST',
        data: {solicitud:'a'},
        dataSrc:""
    },
    dom: 'Brtip',
    initComplete:function( settings, json){
        document.querySelector('.dt-buttons').innerHTML += `<button id="area" class="w-1/2 px-4 py-2 text-sm font-semibold uppercase leading-5 text-center text-white transition-colors duration-150 bg-emerald-500 border border-transparent rounded-lg active:bg-emerald-600 hover:bg-emerald-700 focus:outline-none">Nueva área<i class="fas fa-archive ml-3"></i></button>`;
        const newArea = document.querySelector('#area');

        newArea.addEventListener('click', evt => {
            
            contandorF = 0;
            consumibles_ag = [];

            feedbackname.textContent = ''
            nombreArea.value = '';
            unidadArea.options[0].selected = true;

            agregar.classList.remove('bg-red-500','active:bg-red-600','hover:bg-red-700');
            agregar.classList.add('bg-blue-500','active:bg-blue-600','hover:bg-blue-700');

            html =`
                <tr id="null-data">                    
                    <td colspan="3" class="p-2">
                        <div class="flex justify-center items-center text-sm">
                            <p class="font-medium text-center">Ningún dato disponible en esta tabla</p>
                        </div>
                    </td>
                </tr>
            `;
            
            document.querySelector("#lista-consumible").innerHTML = html;

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

                if(consumibles_ag.length == 0){
                    errores.consumibles = "Por favor, ingrese consumibles";
                    agregar.classList.remove('bg-blue-500','active:bg-blue-600','hover:bg-blue-700');
                    agregar.classList.add('bg-red-500','active:bg-red-600','hover:bg-red-700');
                }

                if(Object.keys(errores).length == 0){
                    $.ajax({
                        url: "./functions.php",
                        type: "POST",
                        datatype:"json",
                        data:  {
                            solicitud: "c",
                            name: nombreArea.value,
                            measure: unidadArea.value,
                            consumables: consumibles_ag
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
    columns: [
        { "data": "id" },
        { "data": "name" },
        { "data": "measure" },
        {
            "data": null,
            render:function(data, type, row)
            {
                if(data['status']){
                    return '<button value="'+data['id']+'" type="button" name="desactivar" class="flex items-center justify-between text-2xl px-1 font-medium leading-5 text-emerald-500 rounded-lg focus:outline-none focus:shadow-outline-gray" onclick="interruptor(this)"><i class="fas fa-toggle-on"></i></button>';
                }
                else{
                    return '<button value="'+data['id']+'" type="button" name="activar" class="flex items-center justify-between text-2xl font-medium px-1 leading-5 text-red-500 rounded-lg focus:outline-none focus:shadow-outline-gray" onclick="interruptor(this)"><i class="fas fa-toggle-off"></i></button>';
                }
            },
            "targets": -1
        },
        {
            "data": null,
            render:function(data, type, row)
            {
                return '<button value="'+data['id']+'" type="button" class="flex items-center justify-between px-2 py-2 text-lg font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray" onclick="editar(this)"><i class="fas fa-edit"></i></i></button>';
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
    titulo_modal_consumible = document.querySelector('#titulo-modal-consumible'),
    guardar = document.querySelector('button[name="guardar"]'),
    nombreArea = document.querySelector('input[name="name"]'),
    unidadArea= document.querySelector('select[name="measure"]'),
    consumible = document.querySelector('input[name="consumable"]'),
    modalConsumible = document.querySelector('#modal-consumible'),
    closeConsumible = document.querySelectorAll('.cancelar-consumible'),
    unit = document.querySelector('input[name="unit"]'),
    printing = document.querySelector('input[name="printing"]'),
    guardarConsumible = document.querySelector('button[name="guardar-consumible"]'),
    agregar = document.querySelector('#agregar'),
    container = document.querySelector('#container-details'),
    inputs = document.querySelectorAll('#modal-consumible-content input'),
    feeds = document.querySelectorAll('.feed');

let consumibles_ag = [];
let contandorF = 0;
let consumible_edit;

agregar.addEventListener('click',evt => {

    titulo_modal_consumible.textContent = 'Nuevo consumible';
    guardarConsumible.textContent = 'Guardar';

    feeds.forEach(x =>{
        x.textContent = '';
    });

    inputs.forEach(x =>{
        x.value = '';
    });

    consumible.value = '';
    unit.value = '';
    printing.value = '';
    
    modalConsumible.classList.toggle('hidden');

    closeConsumible.forEach( x =>{
        x.addEventListener('click', cerrarModalConsumible);
    });

    guardarConsumible.addEventListener('click', guardarModalConsumible);
  
});

function editarConsumible(e) {

    feeds.forEach(x =>{
        x.textContent = '';
    });

    titulo_modal_consumible.textContent = 'Editar consumible';
    guardarConsumible.textContent = 'Actualizar';

    consumible_edit = consumibles_ag.find(x => x.numeroItem == e.value);

    consumible.value = consumible_edit.name;
    unit.value = consumible_edit.unit;
    printing.value = consumible_edit.printing;

    modalConsumible.classList.toggle('hidden');

    closeConsumible.forEach( x =>{
        x.addEventListener('click', cerrarModalConsumible);
    });

    guardarConsumible.addEventListener('click', guardarModalConsumible);
}

function cerrarModalConsumible(e){
    modalConsumible.classList.toggle('hidden');
    guardarConsumible.removeEventListener('click', guardarModalConsumible);
    closeConsumible.forEach( evt =>{
        evt.removeEventListener('click', cerrarModalConsumible);
    });
}

function guardarModalConsumible(evt) {

    let errores = {};

    if(consumible.value.trim().length == 0){
        errores.consumible = 'Por favor, ingrese el nombre del consumible';
        feedbackconsumible.textContent = errores.consumible;
        consumible.addEventListener('change', cambioValor);
    }

    if(unit.value.trim().length == 0 || unit.value == 0){
        errores.unit = 'Por favor, ingrese el precio unitario';
        feedbackunit.textContent = errores.unit;
        unit.addEventListener('change', cambioValor);
    }

    if(printing.value.trim().length == 0 || printing.value == 0){
        errores.printing = 'Por favor, ingrese el precio de impresión';
        feedbackprinting.textContent =  errores.printing;
        printing.addEventListener('change', cambioValor);
    }

    if(Object.keys(errores).length == 0){
        
        if(evt.target.textContent == 'Guardar'){
            agregar.classList.remove('bg-red-500','active:bg-red-600','hover:bg-red-700');
            agregar.classList.add('bg-blue-500','active:bg-blue-600','hover:bg-blue-700');
    
            contandorF++;
            let numeroItem = 'item'+contandorF;
            
            html =`
            <tr id="${numeroItem}">                    
                <td class="p-2">
                    <div class="flex items-center text-sm">
                    <p class="font-medium">${consumible.value}</p>
                    </div>
                </td>
                
                <td class="p-2">
                    <button value="${numeroItem}" type="button" name="desactivar" class="flex items-center justify-between text-xl px-1 font-medium leading-5 text-emerald-500 rounded-lg focus:outline-none focus:shadow-outline-gray" onclick="interruptorConsumible(this)"><i class="fas fa-toggle-on"></i></button>
                </td>        
                
                <td class="p-2">
                    <button value="${numeroItem}" class="flex items-center justify-center p-1 text-base font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-full active:bg-blue-600 hover:bg-blue-700 btn-editar" onclick="editarConsumible(this)">
                    <svg fill="currentColor" viewBox="0 0 20 20" class="h-4 w-4" aria-hidden="true"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                    </button>
                </td>
            </tr>
            `;
        
            if(consumibles_ag.length == 0) document.querySelector("#lista-consumible").innerHTML = html;
            else document.querySelector("#lista-consumible").innerHTML += html;
            
            consumibles_ag.push({
                numeroItem: numeroItem,
                name: consumible.value,
                unit: unit.value,
                printing: printing.value,
                status: 1
            });
            
            Toastify({
                text: "Consumible ingresado!",
                duration: 3000,
                style: {
                    background: '#10B981'
                }
            }).showToast();
        }
        else{
            consumible_edit.name = consumible.value;
            consumible_edit.unit = unit.value;
            consumible_edit.printing = printing.value;

            document.querySelector('#'+consumible_edit.numeroItem+' td p').textContent = consumible.value;
        }

        modalConsumible.classList.toggle('hidden');
    }

}

function cambioValor(e) {
    e.target.nextElementSibling.textContent = '';
    e.target.removeEventListener('change',cambioValor);
}

function editar(e){
    contandorF = 0;
    consumibles_ag = [];

    feedbackname.textContent ='';
    document.querySelector("#lista-consumible").innerHTML = '';

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
            nombreArea.value = data['area']['name'];
            unidadArea.value = data['area']['measure'];
            data['consumables'].forEach((x) => {
                contandorF++;

                let numeroItem = 'item'+contandorF;

                const statusButton = x.status ? `<button value="${numeroItem}" type="button" name="desactivar" class="flex items-center justify-between text-xl px-1 font-medium leading-5 text-emerald-500 rounded-lg focus:outline-none focus:shadow-outline-gray" onclick="interruptorConsumible(this)"><i class="fas fa-toggle-on"></i></button>`: `<button value="${numeroItem}" type="button" name="activar" class="flex items-center justify-between text-xl px-1 font-medium leading-5 text-red-500 rounded-lg focus:outline-none focus:shadow-outline-gray" onclick="interruptorConsumible(this)"><i class="fas fa-toggle-off"></i></button>`;

                html =`
                <tr id="${numeroItem}">                    
                    <td class="p-2">
                    <div class="flex items-center text-sm">
                    <p class="font-medium">${x.name}</p>
                    </div>
                    </td>
                    
                    <td class="p-2">
                        ${statusButton}
                    </td>
                
                    <td class="p-2">
                        <button value="${numeroItem}" class="flex items-center justify-center p-1 text-base font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-full active:bg-blue-600 hover:bg-blue-700 btn-editar" onclick="editarConsumible(this)">
                        <svg fill="currentColor" viewBox="0 0 20 20" class="h-4 w-4" aria-hidden="true"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                        </button>
                    </td>
                </tr>
                `;
                
                consumibles_ag.push({
                    numeroItem: numeroItem,
                    name: x.name,
                    unit: x.unit_price,
                    printing: x.printing_price,
                    status: x.status
                });

                document.querySelector("#lista-consumible").innerHTML += html;
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
                            measure: unidadArea.value,
                            consumables: consumibles_ag,
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

function interruptorConsumible(e) {

    consumible_edit = consumibles_ag.find(x => x.numeroItem == e.value);

    if(e.name == "activar"){
        consumible_edit.status = 1;
        e.classList.remove('text-red-500');
        e.classList.add('text-emerald-500');
        e.innerHTML = '<i class="fas fa-toggle-on"></i>';
        e.name = 'desactivar';
    }
    else{
        consumible_edit.status = 0;
        e.classList.remove('text-emerald-500');
        e.classList.add('text-red-500');
        e.innerHTML = '<i class="fas fa-toggle-off"></i>';
        e.name = 'activar';
    }

}