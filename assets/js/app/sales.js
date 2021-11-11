const tipoDocumento = document.querySelector('select[name="tipodocumento"]'),
    tituloDocumento=  document.querySelector('#tituloDocumento'),
    inputDocumento = document.querySelector('input[name="documento"]'),
    idHidden = document.querySelector('input[type="hidden"]'),
    agregar = document.querySelector('#agregar'),
    fecha = document.querySelector('input[name="fecha"]'),
    categoria_servicio = document.querySelector('select[name="categoria_servicio"]'),
    servicio = document.querySelector('select[name="servicio"]'),
    nombreCliente = document.querySelector('input[name="name"]'),
    closeModal = document.querySelectorAll('.close'),
    modal = document.querySelector('#modal'),
    guardar = document.querySelector('button[name="guardar"]'),
    moda_content = document.querySelector('#modal-content');

let servicios = [];

//Informacion inicial
fetch('../api.php',{
    method: "POST",
    mode: "same-origin",
    credentials: "same-origin",
    headers: {
        "Content-Type": "application/json"
    },
    body: JSON.stringify({datos: {
            solicitud: "s",
        }})
})
    .then(res => res.json())
    .then(data =>{
        servicios = data;
        console.log(servicios);
        servicios['areas'].forEach( (e) =>{
                servicio.innerHTML += `<option value="${e.id}" >${e.name}</option>`;
        });

    });

let consumibles = [];

fetch('../api.php',{
    method: "POST",
    mode: "same-origin",
    credentials: "same-origin",
    headers: {
        "Content-Type": "application/json"
    },
    body: JSON.stringify({datos: {
            solicitud: "cons",
        }})
})
    .then(res => res.json())
    .then(data =>{
        consumibles = data;
    });

//Cambio de categoria servicio
categoria_servicio.addEventListener('change', evt => {

    servicio.innerHTML = '';
    servicios[categoria_servicio.value].forEach( (e) =>{
        servicio.innerHTML += `<option value="${e.id}" >${e.name}</option>`;
    });

});

//Tipo de documento -> RUC/CEDULA/PASAPORTE
const TIPOS_DOCUMENTOS = {
    C: () => {
        tituloDocumento.textContent = 'Número de cédula';
        inputDocumento.placeholder = "Ingrese el número de cédula con guiones";
    },
    R: () => {
        tituloDocumento.textContent = 'Número de RUC';
        inputDocumento.placeholder = "Ingrese el número de RUC con guiones";
    },
    P: () => {
        tituloDocumento.textContent = 'Número de Pasaporte';
        inputDocumento.placeholder = "Ingrese el número de pasaporte con guiones";
    }
}

tipoDocumento.addEventListener('change', evt => {
    TIPOS_DOCUMENTOS[evt.target.value]();
    inputDocumento.value = '';
    triggerKeyup(inputDocumento)

});

$( function() {
    // Single Select
    $("#autoComplete").autocomplete({

        source: function (request, response) {
            $.ajax({
                url: "../ajax.php",
                type: 'post',
                dataType: "json",
                data: {
                    customers: request.term,
                    document_type: tipoDocumento.value
                },
                success: function (data) {
                    try{
                        response(data.slice(0, 3));
                    }
                    catch (e) {
                        response(data);
                    }

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
                backgroundColor: "#10B981",
            }).showToast();

            return false;
        }

    });


});

function triggerKeyup(element){
    let changeEvent = new Event('keyup');
    element.dispatchEvent(changeEvent);
}

inputDocumento.addEventListener('keyup', evt => {

    if(evt.key != "Enter"){
        idHidden.value = '';
        nombreCliente.value = '';
    }

});

agregar.addEventListener('click',evt => {
    let servicioAdd = servicios[categoria_servicio.value].find((value) => value.id == servicio.value);
    registrarServicio(categoria_servicio.value,servicioAdd.id,servicioAdd.name,servicioAdd.price);
});

let id_cliente = 0;
let servicios_ag = [];

let contandorF = 0;
let html = '';
let sw= 0;

function registrarServicio(categoria_servicio,id_servicio, descripcion, precio = 0.00) {

    contandorF++;

    let numeroItem = 'item'+contandorF;

    servicios_ag.push({
        categoria: categoria_servicio,
        servicio: id_servicio,
        numeroItem: numeroItem
    });

    if(sw === 0 ){
        sw=1;
        document.querySelector("#detalle_totales").classList.remove('hidden');
        document.querySelector("#acciones").classList.remove('hidden');
    }

    Toastify({
        text: "Servicio ingresado!",
        duration: 3000,
        backgroundColor: "#10B981",
    }).showToast();

    html =`
        <tr id="${numeroItem}">
            <td class="px-4 py-4">
              <div class="flex items-center text-sm">
              <button class="flex items-center justify-center p-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-full active:bg-blue-600 hover:bg-blue-700" onclick="editarItem('${categoria_servicio}',${id_servicio},${numeroItem})">
<svg fill="currentColor" viewBox="0 0 20 20" class="h-5 w-5" aria-hidden="true"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
            </button>
              </div>
            </td>                    
            <td class="px-4 py-4 w-1/4">
              <div class="flex items-center text-sm">
                  <p class="font-semibold">${descripcion}</p>
              </div>
            </td>
            
            <td class="px-4 py-4 w-1/6">
                <input type="number" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" name="precio" value="${precio}" min="0.00" step="0.01" onchange="cambiarValue(this,total_item${contandorF})" >
            </td>
            <td class="px-4 py-4 w-1/6">
                <input type="number" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" name="cantidad" value="1" disabled>
            </td>
            
            <td class="px-4 py-4 text-sm font-semibold" id="total_item${contandorF}">
              ${precio}
            </td>
            <td class="px-4 py-4">
              <div class="flex items-center space-x-4 text-sm">
                <button
                  class="flex items-center justify-between px-2 py-2 text-base font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray"
                  aria-label="Delete"
                  onclick="deleteServicio(${numeroItem})"
                >
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
            </td>
          </tr>
        `;

    document.querySelector("#detalle_venta").innerHTML += html;

    calcular();

}

function editarItem(categoria_servicio,id_servicio,numeroItem){
    TIPO_TABLAS[categoria_servicio](categoria_servicio,id_servicio,numeroItem.id);
    modal.classList.toggle('hidden');

    //guardar.addEventListener('click', actualizar);

    closeModal.forEach( e =>{
        e.addEventListener('click', cerrarActualizar);
    });

    function cerrarActualizar(e){
        modal.classList.toggle('hidden');
        //guardar.removeEventListener('click', actualizar);
        closeModal.forEach( e =>{
            e.removeEventListener('click', cerrarActualizar);
        });
    }
}

const TIPO_TABLAS = {
    "membresias" : (categoria,id_servicio,numeroItem_id) =>{
        moda_content.innerHTML = `
                    <main class="flex justify-between flex-wrap items-center mb-5">
                    <div class="w-full shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <spa></spa>
                        <table class="w-full min-w-full divide-y divide-gray-200">
                        <tbody class="divide-y divide-gray-200">
                        <tr>
                            <th class="bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Fecha inicial</th>
                            <th class="bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Fecha final</th>
                        </tr>
                        <tr>
                        <td class="px-3 py-3 whitespace-nowrap"><input class="text-sm mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="date" name="" required=""></td>
                            
                        <td class="px-3 py-3 whitespace-nowrap"><input class="text-sm mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="date" name="" required=""></td>
</tr>
                        </tbody>
                        </table>
                    </div>
                    </main>
               `;
    },
    "eventos": (categoria,id_servicio,numeroItem_id)=>{
        moda_content.innerHTML = `<main class="flex justify-between flex-wrap items-center mb-5">
                    <div class="w-full shadow overflow-auto border-b border-gray-200 sm:rounded-lg">
                    <spa></spa>
                        <table class="w-full min-w-full divide-y divide-gray-200">
                        <tbody>
                        <tr>
                            <th class="bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Nombre</th>
                            <th class="bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Cantidad de horas</th>
                        </tr>
                        <tr>
                            <td class="px-2 py-2"><input class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="text" name="" required=""></td>
                          <td class="px-2 py-2"><input class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="" required=""></td>
                        </tr>
                        <tr>
                            <th class="bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Fecha inicial</th>

                            <th class="bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Fecha final</th>
                        </tr>
                        <tr>
                          <td class="px-2 py-2"><input class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="date" name="" required=""></td>

                          <td class="px-2 py-2"><input class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="date" name="" required=""></td>
                        </tr>
                        <tr>
                            <th class="bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Gastos</th>
                            <th class="bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Descripcíon gastos</th>
                        </tr>
                        <tr>
                          <td class="px-2 py-2"><input class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="" required=""></td>
                            <td class="px-2 py-2"><textarea class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea></td>
                        </tr>                        
                        </tbody>
                        </table>
                    </div>
                    </main>`;
    },
    "areas": (categoria,id_servicio,numeroItem_id)=>{
        let indice = servicios_ag.findIndex((value) => value.numeroItem == numeroItem_id);
        let respuesta = servicios_ag[indice].hasOwnProperty('datos');

        moda_content.innerHTML = `<main class="flex justify-center flex-wrap items-center mb-5">
                    <div class="flex items-center mr-4 mb-2">
                    <span class="inline-block w-3 h-3 mr-1 bg-red-100 rounded-full"></span>
                    <span class="category">Costo del consumible</span>
                  </div>
                  <div class="flex items-center mb-2">
                    <span class="inline-block w-3 h-3 mr-1 bg-green-100 rounded-full"></span>
                    <span class="category">Costo de impresión</span>
                  </div>
                    <div class="w-full shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <spa></spa>
                    <table class="w-full min-w-full divide-y divide-gray-200">
                        <tbody>
                        <tr>
                            <th colspan="2" class="bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Tipo de suplemento</th>
                        </tr>
                        <tr>
                            <td colspan="2" class="px-2 py-2"><select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required="" name="tipo_consumible">
                            </select></td>
                        </tr>
                        <tr>
                            <th class="bg-red-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Cantidad (u de medida)</th>
                            <th class="bg-red-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Precio unitario (disab)</th>
                        </tr>
                        <tr>
                            <td class="px-2 py-2"><input class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="" required=""></td>
                            <td class="px-2 py-2"><input class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="" required=""></td>
                        </tr>
                        <tr>
                            <th class="bg-green-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Tiempo impresión</th>
                            <th class="bg-green-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Precio impresión</th>
                        </tr>
                        <tr>
                            <td class="px-2 py-2"><input class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="" required=""></td>
                            <td class="px-2 py-2"><input class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="" required=""></td>
                        </tr>
                        <tr>
                            <th colspan="2" class="bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Costo base</th>
                        </tr>
                        <tr>
                            <td colspan="2" class="px-2 py-2"><input class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="" required=""></td>
                        </tr>
                        <tr>
                            <th class="bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Porcentaje ganancia</th>
                            <th class="bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Porcentaje descuento</th>
                        </tr>
                        <tr>
                            <td class="px-2 py-2"><input class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="" required=""></td>
                            <td class="px-2 py-2"><select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required="" name="tipodocumento">
                                <option value="R">RUC</option>
                                <option value="C">Cédula</option>
                                <option value="P">Pasaporte</option>
                            </select></td>
                        </tr>
                        <tr>
                            <th colspan="2" class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left bg-gray-100">Costo total</th>
                        </tr>
                        <tr>
                            <td colspan="2" class="px-2 py-2"><input class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="" required=""></td>
                        </tr>
                        </tbody>
                        </table>
                    </div>
                    </main>`;
    },
    "alquiler": (categoria,id_servicio,numeroItem_id)=>{
        moda_content.innerHTML = `
                    <main class="flex justify-between flex-wrap items-center mb-5">
                    <div class="w-full shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <spa></spa>
                        <table class="w-full min-w-full divide-y divide-gray-200">
                        <tbody class="divide-y divide-gray-200">
                        <tr>
                            <th class="bg-gray-100 w-1/4 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Cantidad de horas</th>
                        <td class="px-3 py-3 whitespace-nowrap"><input class="text-sm mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="" required=""></td>
                        </tr>
                        </tbody>
                        </table>
                    </div>
                    </main>
               `;
    }
};

function cerrarActualizar(e){
    modal.classList.toggle('hidden');
    guardar.removeEventListener('click', actualizar);
    description.value = '';
    fecha.value = '';
    closeModal.forEach( e =>{
        e.removeEventListener('click', cerrarActualizar);
    });
}

function cambiarValue(elm,columna_total){
    elm.defaultValue = elm.value;
    TIPOS_CAMBIOS[elm.name](elm,columna_total);
}

const TIPOS_CAMBIOS = {
    precio: (elm,columna_total) => {
        let cantidad = elm.parentElement.nextElementSibling.children[0];
        columna_total.textContent = (elm.value * cantidad.value).toFixed(2);
        calcular();
    },
    cantidad: (elm,columna_total) => {
        let v = parseInt(elm.value);
        if (v < 1) elm.value = 1;
        if (v > 4) elm.value = 4;

        let precio = elm.parentElement.previousElementSibling.children[0];

        columna_total.textContent = (elm.value * precio.value).toFixed(2);
        calcular();
    }
}

function deleteServicio(id_tr){

    Toastify({
        text: "Servicio eliminado!",
        duration: 3000,
        backgroundColor: "#EF4444",
    }).showToast();

    let indice = servicios_ag.findIndex((value) => value.numeroItem == id_tr.id) // obtenemos el indice
    servicios_ag.splice(indice, 1); // 1 es la cantidad de elemento a eliminar
    id_tr.remove();

    html= document.querySelector("#detalle_venta").innerHTML;

    if(html.trim() == ''){
        document.querySelector("#detalle_totales").classList.add('hidden');
        document.querySelector("#acciones").classList.add('hidden');
        sw=0;
        contandorF = 0;
    }
    else{
        calcular();
    }
}

const ITBMS = 0.07;

function calcular() {
    // obtenemos todas las filas del tbody
    let filas = document.querySelectorAll("#detalle_venta tr");

    let subtotal = 0;
    let itbmstotal =0;

    // recorremos cada una de las filas
    filas.forEach((e) => {

        // obtenemos las columnas de cada fila
        let columnas = e.querySelectorAll("td");

        // obtenemos los valores de la cantidad y importe
        let importe = parseFloat(columnas[4].textContent);

        subtotal += importe;
        itbmstotal += importe * ITBMS;
    });

    filas = document.querySelectorAll("#detalle_totales tr td");
    filas[1].textContent = subtotal.toFixed(2);
    filas[3].textContent = itbmstotal.toFixed(2);
    filas[5].textContent = (subtotal+itbmstotal).toFixed(2);

}

let btn_anular = document.querySelector('#anular');

btn_anular.addEventListener('click',e =>{
    location.reload();
});