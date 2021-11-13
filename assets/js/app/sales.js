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
                style: {
                    background: '#10B981'
                }
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
    registrarServicio(categoria_servicio.value,servicioAdd.id,servicioAdd.name,servicioAdd.price,servicioAdd.measure);
});

let id_cliente = 0;
let servicios_ag = [];

let contandorF = 0;
let html = '';
let sw= 0;

function registrarServicio(categoria_servicio,id_servicio, descripcion, precio = '0.00',measure = 'S/U') {

    contandorF++;

    let numeroItem = 'item'+contandorF;
    servicios_ag.push({
        categoria: categoria_servicio,
        servicio: id_servicio,
        numeroItem: numeroItem,
        cantidad: 1
    });

    if(sw === 0 ){
        sw=1;
        document.querySelector("#detalle_totales").classList.remove('hidden');
        document.querySelector("#acciones").classList.remove('hidden');
    }

    let validar = (categoria_servicio == 'areas');

    Toastify({
        text: "Servicio ingresado!",
        duration: 3000,
        style: {
            background: '#10B981'
        }
    }).showToast();

    html =`
        <tr id="${numeroItem}">
            <td class="px-4 py-4">
              <div class="flex items-center text-sm">
              <button class="flex items-center justify-center p-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-full active:bg-blue-600 hover:bg-blue-700" onclick="editarItem('${categoria_servicio}',${id_servicio},${numeroItem},'${measure}')">
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
                <input type="number" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 precio ${ validar ? 'bg-gray-300 cursor-not-allowed' : ''}" value="${precio}" min="0.00" step="0.01" onchange="cambiarValue(this,total_item${contandorF})" ${ validar ? 'disabled' : ''} >
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

let respuesta;

function editarItem(categoria_servicio,id_servicio,numeroItem,unidad){
    indice = servicios_ag.findIndex((value) => value.numeroItem == numeroItem.id);

    respuesta = servicios_ag[indice].hasOwnProperty('detalles');

    TIPO_TABLAS[categoria_servicio](categoria_servicio,id_servicio,numeroItem.id,unidad);

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
        modal.classList.toggle('hidden');

        TIPO_ACTUALIZAR[categoria_servicio](numeroItem.id);

        guardar.removeEventListener('click', actualizar);
        closeModal.forEach( e =>{
            e.removeEventListener('click', cerrarActualizar);
        });
    }
}

function triggerChange(element){
    let changeEvent = new Event('change');
    element.dispatchEvent(changeEvent);
}

let indice;
let areaConsumibles = [];
let selectorConsumible;
let selectorDescuento;

let inputPrecioUnitario;
let inputPrecioImpresion;

let inputCantidadUnidad;
let inputCantidadTiempo;

let inputCostoBase;
let inputCostoTotal;

let costoBase; //Calculo en linea

const TIPO_TABLAS = {
    "membresias" : (categoria,id_servicio,numeroItem_id,unidad) =>{
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
                        <td class="px-3 py-3 whitespace-nowrap"><input class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="date" name="fecha_inicial" value="${respuesta ? servicios_ag[indice].detalles.fecha_inicial : ''}" </td>
                            
                        <td class="px-3 py-3 whitespace-nowrap"><input class="text-sm  w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="date" name="fecha_final" value="${respuesta ? servicios_ag[indice].detalles.fecha_final : ''}"></td>
</tr>
                        </tbody>
                        </table>
                    </div>
                    </main>
               `;
    },
    "eventos": (categoria,id_servicio,numeroItem_id,unidad)=>{
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
                            <td class="px-2 py-2"><input class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="text" placeholder="Ingrese el nombre del evento" name="nombre_evento" value="${respuesta ? servicios_ag[indice].detalles.nombre_evento : ''}"></td>
                          <td class="px-2 py-2"><input class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" placeholder="Ingrese la cantidad de horas del evento" name="cantidad_horas" min="1" value="${respuesta ? servicios_ag[indice].detalles.cantidad_horas : ''}"></td>
                        </tr>
                        <tr>
                            <th class="bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Fecha inicial</th>

                            <th class="bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Fecha final</th>
                        </tr>
                        <tr>
                          <td class="px-2 py-2"><input class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="date" name="fecha_inicial" value="${respuesta ? servicios_ag[indice].detalles.fecha_inicial : ''}"></td>

                          <td class="px-2 py-2"><input class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="date" name="fecha_final" value="${respuesta ? servicios_ag[indice].detalles.fecha_final : ''}"></td>
                        </tr>
                        <tr>
                            <th class="bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Gastos</th>
                            <th class="bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Descripcíon gastos</th>
                        </tr>
                        <tr>
                          <td class="px-2 py-2"><input class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" placeholder="Ingrese los gastos del evento" name="gastos_evento" min="0.00" step="0.01" value="${respuesta ? servicios_ag[indice].detalles.gastos_evento : ''}"></td>
                            <td class="px-2 py-2"><textarea class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Describa los gastos del evento" name="desc_gastos">${respuesta ? servicios_ag[indice].detalles.desc_gastos : ''}</textarea></td>
                        </tr>                        
                        </tbody>
                        </table>
                    </div>
                    </main>`;
    },
    "areas": (categoria,id_servicio,numeroItem_id,unidad)=>{

        areaConsumibles = consumibles.filter(value => value.area_id == id_servicio);

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
                            <th colspan="2" class="bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Tipo de consumible</th>
                        </tr>
                        <tr>
                            <td colspan="2" class="px-2 py-2"><select class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required="" name="tipo_consumible" onchange="cambiarPrecioUnitario()">
                            </select></td>
                        </tr>
                        <tr>
                            <th class="bg-red-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">${unidad}</th>
                            <th class="bg-red-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Precio unitario</th>
                        </tr>
                        <tr>
                            <td class="px-2 py-2"><input class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" placeholder="Ingrese la cantidad de ${unidad.toLowerCase()}" name="cantidad_unidad" value="${respuesta ? servicios_ag[indice].detalles.cantidad_unidad : ''}" min="0" onchange="cambiarTotales()"></td>
                            <td class="px-2 py-2"><input class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300" type="number" name="precio_unitario" min="0.00" step="0.01" onchange="cambiarTotales()"></td>
                        </tr>
                        <tr>
                            <th class="bg-green-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Tiempo impresión (minutos)</th>
                            <th class="bg-green-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Precio impresión</th>
                        </tr>
                        <tr>
                            <td class="px-2 py-2"><input class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" placeholder="Ingrese la cantidad de minutos" name="cantidad_tiempo" value="${respuesta ? servicios_ag[indice].detalles.cantidad_tiempo : ''}" min="0" onchange="cambiarTotales()"></td>
                            <td class="px-2 py-2"><input class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300" type="number" name="precio_impresion" value="0.05" min="0.00" step="0.01" onchange="cambiarTotales()"></td>
                        </tr>
                        <tr>
                            <th colspan="2" class="bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Costo base</th>
                        </tr>
                        <tr>
                            <td colspan="2" class="px-2 py-2"><input class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="number" name="costo_base" value="${respuesta ? servicios_ag[indice].detalles.costo_base : '0.00'}" disabled></td>
                        </tr>
                        <tr>
                            <th class="bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Porcentaje ganancia</th>
                            <th class="bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Porcentaje descuento</th>
                        </tr>
                        <tr>
                            <td class="px-2 py-2"><select class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" required="" name="tipo_ganancia" disabled>
                            <option value="0.3" selected>30%</option>
                            </select></td>
                            <td class="px-2 py-2"><select class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" name="tipo_descuento" onchange="cambiarCostoTotal()">
                            <option value="0" >Público - 0%</option>
                            <option value="0.1" >Docente - 10%</option>
                            <option value="0.15" >Estudiante - 15%</option>
                            </select></td>
                        </tr>
                        <tr>
                            <th colspan="2" class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left bg-gray-100">Costo total</th>
                        </tr>
                        <tr>
                            <td colspan="2" class="px-2 py-2"><input class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="number" name="costo_total" value="${respuesta ? servicios_ag[indice].detalles.costo_total : '0.00'}" disabled></td>
                        </tr>
                        </tbody>
                        </table>
                    </div>
                    </main>`;

        selectorConsumible = document.querySelector('select[name="tipo_consumible"]'),
        selectorDescuento = document.querySelector('select[name="tipo_descuento"]'),
        inputPrecioUnitario = document.querySelector('input[name="precio_unitario"]'),
        inputPrecioImpresion = document.querySelector('input[name="precio_impresion"]'),
        inputCantidadUnidad = document.querySelector('input[name="cantidad_unidad"]'),
        inputCantidadTiempo = document.querySelector('input[name="cantidad_tiempo"]'),
            inputCostoBase = document.querySelector('input[name="costo_base"]'),
            inputCostoTotal = document.querySelector('input[name="costo_total"]');

        selectorConsumible.innerHTML = '';

        if(areaConsumibles.length){
            areaConsumibles.forEach( (value) =>{
                selectorConsumible.innerHTML += `<option value="${value.consumable_id}" ${respuesta ? servicios_ag[indice].detalles.tipo_consumible == value.consumable_id ? 'selected' : '' : ''} >${value.name}</option>`;
            } )

            if(respuesta){
                Array.from(selectorDescuento.options).forEach( (opt) =>{
                    if(opt.value == servicios_ag[indice].detalles.porcentaje_descuento){
                        opt.selected = true;
                    }
                });
            }

            cambiarPrecioUnitario();
        }
        else{
            selectorConsumible.innerHTML = `<option value="null" selected>No existen consumibles</option>`;
            inputPrecioUnitario.value = '0.00';
        }

    },
    "alquiler": (categoria,id_servicio,numeroItem_id,unidad)=>{
        moda_content.innerHTML = `
                    <main class="flex justify-between flex-wrap items-center mb-5">
                    <div class="w-full shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <spa></spa>
                        <table class="w-full min-w-full divide-y divide-gray-200">
                        <tbody class="divide-y divide-gray-200">
                        <tr>
                            <th class="bg-gray-100 w-1/4 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-left">Cantidad de horas</th>
                        <td class="px-3 py-3 whitespace-nowrap"><input class="text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" placeholder="Ingrese la cantidad de horas del alquiler" name="cantidad_horas" min="1" value="${respuesta ? servicios_ag[indice].detalles.cantidad_horas : ''}"></td>
                        </tr>
                        </tbody>
                        </table>
                    </div>
                    </main>
               `;
    }
};

function cambiarPrecioUnitario(){
    let consumibleSeleccionado = areaConsumibles.find(value => value.consumable_id == selectorConsumible.value);

    inputPrecioUnitario.value = consumibleSeleccionado.unit_price;

    cambiarTotales();
}

function cambiarTotales(){

    if(inputCantidadUnidad.value != 0 && inputCantidadTiempo.value != 0 && inputPrecioUnitario.value != 0){

        costoBase = (inputCantidadUnidad.value * inputPrecioUnitario.value) + (inputCantidadTiempo.value * inputPrecioImpresion.value);
        inputCostoBase.value = costoBase.toFixed(2);

        inputCostoTotal.value = (costoBase * (1-selectorDescuento.value)).toFixed(2);

    }
}
function cambiarCostoTotal(){

    if(inputCostoTotal.value != 0){
        inputCostoTotal.value = (costoBase* (1-selectorDescuento.value)).toFixed(2);
    }
}

const TIPO_ACTUALIZAR = {
    "membresias": (numeroItem)=>{
        //Aqui iria la validacion de los elementos...

        servicios_ag[indice].detalles = {
            fecha_inicial: document.querySelector('input[name="fecha_inicial"]').value,
            fecha_final: document.querySelector('input[name="fecha_final"]').value
        }
    },
    "eventos": (numeroItem)=>{
        servicios_ag[indice].detalles = {
            nombre_evento: document.querySelector('input[name="nombre_evento"]').value,
            cantidad_horas: document.querySelector('input[name="cantidad_horas"]').value,
            fecha_inicial: document.querySelector('input[name="fecha_inicial"]').value,
            fecha_final: document.querySelector('input[name="fecha_final"]').value,
            gastos_evento: document.querySelector('input[name="gastos_evento"]').value,
            desc_gastos: document.querySelector('textarea[name="desc_gastos"]').value
        }
    },
    "areas": (numeroItem)=>{
        servicios_ag[indice].detalles = {
            tipo_consumible: selectorConsumible.value,
            cantidad_unidad: inputCantidadUnidad.value,
            precio_unitario: inputPrecioUnitario.value,
            cantidad_tiempo: inputCantidadTiempo.value,
            precio_impresion: inputPrecioImpresion.value,
            costo_base: inputCostoBase.value,
            porcentaje_ganancia: document.querySelector('select[name="tipo_ganancia"]').value,
            porcentaje_descuento: selectorDescuento.value,
            costo_total: inputCostoTotal.value
        }

        const precio = document.querySelector('#'+numeroItem+' .precio');
        precio.value = inputCostoTotal.value;

        triggerChange(precio);
    },
    "alquiler": (numeroItem)=>{
        servicios_ag[indice].detalles = {
            cantidad_horas: document.querySelector('input[name="cantidad_horas"]').value
        }
    },
}

function cambiarValue(elm,columna_total){

    elm.defaultValue = elm.value;
    let cantidad = elm.parentElement.nextElementSibling.children[0];
    columna_total.textContent = (elm.value * cantidad.value).toFixed(2);
    calcular();
}

function deleteServicio(id_tr){

    Toastify({
        text: "Servicio eliminado!",
        duration: 3000,
        style: {
            background: '#EF4444'
        }
    }).showToast();

    indice = servicios_ag.findIndex((value) => value.numeroItem == id_tr.id) // obtenemos el indice
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