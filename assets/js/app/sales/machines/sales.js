const TIPO_TABLA = {
    'Electrónica': (reason, id_servicio, numeroItem) => {
        return TablaElectronica(reason, id_servicio, numeroItem);
    },
    'Mini Fresadora CNC': (reason, id_servicio, numeroItem) => {
        return TablaMiniFresadoraCNC(reason, id_servicio, numeroItem);
    },
    'Láser CNC': (reason, id_servicio, numeroItem) => {
        return TablaLaserCNC(reason, id_servicio, numeroItem);
    },
    'Cortadora de Vinilo': (reason, id_servicio, numeroItem) => {
        return TablaCortadoraVinilo(reason, id_servicio, numeroItem);
    },
    'Impresión 3D en filamento': (reason, id_servicio, numeroItem) => {
        return TablaImpresion3DEnFilamento(reason, id_servicio, numeroItem);
    },
    'Impresión 3D en resina': (reason, id_servicio, numeroItem) => {
        return TablaImpresion3DEnResina(reason, id_servicio, numeroItem);
    },
    'Software de diseño': (reason, id_servicio, numeroItem) => {
        return TablaSoftwareDeDiseño(reason, id_servicio, numeroItem);
    },
    'Bordadora CNC': (reason, id_servicio, numeroItem) => {
        return TablaBordadoraCNC(reason, id_servicio, numeroItem);
    }
};

const TIPO_ACTUALIZAR = {
    'Electrónica': (numeroItem) => {
        return saveElectronica(numeroItem);
    },
    'Mini Fresadora CNC': (numeroItem) => {
        return saveMiniFresadoraCNC(numeroItem);
    },
    'Láser CNC': ( numeroItem) => {
        return saveLaserCNC( numeroItem);
    },
    'Cortadora de Vinilo': ( numeroItem) => {
        return saveCortadoraVinilo( numeroItem);
    },
    'Impresión 3D en filamento': (numeroItem) => {
        return saveImpresion3DEnFilamento(numeroItem);
    },
    'Impresión 3D en resina': ( numeroItem) => {
        return saveImpresion3DEnResina(numeroItem);
    },
    'Software de diseño': (numeroItem) => {
        return saveSoftwareDeDiseño(numeroItem);
    },
    'Bordadora CNC': (numeroItem) => {
        return saveBordadoraCNC(numeroItem);
    }
}

const PRICE_HOUR = {
    'Electrónica': 1,
    'Mini Fresadora CNC' : 10,
    'Láser CNC' : 10,
    'Cortadora de Vinilo' : 5,
    'Impresión 3D en filamento' : 2,
    'Impresión 3D en resina' : 4,
    'Bordadora CNC' : 5
}

const PRICE_MACHINE = {
    'Mini Fresadora CNC' : 4000,
    'Láser CNC' : 9000,
    'Cortadora de Vinilo' : 2000,
    'Impresión 3D en filamento' : 400,
    'Impresión 3D en resina' : 800,
    'Bordadora CNC' : 1000
}

const CALCULATE_SUPPLIES = {
    'Electrónica': () => {
        return calculateSuppliesElectronica();
    },
    'Mini Fresadora CNC': (e) => {
        return calculateSuppliesMiniFresadoraCNC();
    },
    'Láser CNC': () => {
        return calculateSuppliesLaserCNC();
    },
    'Cortadora de Vinilo': () => {
        return calculateSuppliesCortadoraVinilo();
    },
    'Impresión 3D en filamento': () => {
        return calculateSuppliesImpresion3DEnFilamento();
    },
    'Impresión 3D en resina': () => {
        return calculateSuppliesImpresion3DEnResina();
    },
    'Software de diseño': () => {
        return calculateSuppliesSoftwareDeDiseño();
    },
    'Bordadora CNC': () => {
        return calculateSuppliesBordadoraCNC();
    }
}


agregar.addEventListener('click', evt => {
    let servicioAdd = servicios.find((value) => value.id == servicio.value);
    registrarServicio(servicioAdd.id, servicioAdd.name);
});

let servicios_ag = [];
let indice;

let contandorF = 0;
let html = '';
let htmlTable = '';

function registrarServicio(id_servicio, descripcion, precio = '0.00') {

    contandorF++;

    let numeroItem = 'item' + contandorF;

    Toastify({
        text: "Servicio ingresado!",
        duration: 3000,
        style: {
            background: '#10B981'
        }
    }).showToast();

    html = `
        <tr id="${numeroItem}">
            <td class="px-4 py-4">
              <div class="flex items-center text-sm">
              <button class="flex items-center justify-center p-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-full active:bg-blue-600 hover:bg-blue-700 btn-detalles" onclick="editarItem('${descripcion}', ${id_servicio},'${numeroItem}')">
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
                <input type="number" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 precio" value="0.00" placeholder="Precio" min="0.00" step="0.01" onchange="cambiarValue(this,total_item${contandorF})"} >
            </td>
            
            <td class="px-4 py-4 text-sm font-semibold" id="total_item${contandorF}">
              ${precio}
            </td>
            <td class="px-4 py-4">
              <div class="flex items-center space-x-4 text-sm">
                <button
                  class="flex items-center justify-between px-2 py-2 text-xl font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray"
                  aria-label="Delete"
                  onclick="deleteServicio(${numeroItem})"
                >
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
            </td>
          </tr>
        `;

    if (servicios_ag.length == 0) {
        document.querySelector("#detalle_venta").innerHTML = html;
        document.querySelector("#detalle_totales").classList.remove('hidden');
        document.querySelector("#acciones").classList.remove('hidden');
    }
    else {
        document.querySelector("#detalle_venta").innerHTML += html;
    }

    servicios_ag.push({
        servicio: id_servicio,
        numeroItem: numeroItem
    });

    calcular();

}

let respuesta;

function editarItem(descripcion, id_servicio, numeroItem) {
    indice = servicios_ag.findIndex((value) => value.numeroItem == numeroItem);

    respuesta = servicios_ag[indice].hasOwnProperty('detalles');

    modal_content.classList.add('max-h-96', 'overflow-auto');
    htmlTable = '';
    TIPO_TABLA[descripcion](reason, id_servicio, numeroItem);

    modal.classList.toggle('hidden');
    guardar.addEventListener('click', actualizar);
    closeModal.forEach(e => {
        e.addEventListener('click', cerrarActualizar);
    });

    function cerrarActualizar(e) {
        modal.classList.toggle('hidden');
        Swal.fire({
            title: 'Advertencia',
            text: "¿Seguro que quieres salir sin guardar?",
            icon: 'warning',
            allowOutsideClick: false,
            showCancelButton: true,
            confirmButtonColor: '#10B981',
            cancelButtonColor: '#EF4444',
            confirmButtonText: 'Si, salir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                guardar.removeEventListener('click', actualizar);
                closeModal.forEach(e => {
                    e.removeEventListener('click', cerrarActualizar);
                });
                modal_content.classList.remove('max-h-96', 'overflow-auto');
                
                    components_ag = [];
                    materials_milling_ag = [];
                    materials_laser_ag = [];
                    vinyls_ag = [];
                    filaments_ag = [];
                    resins_ag = [];
                    threads_ag = [];

                    costHoursArea = 0;
                    costMinutesArea = 0;
                    currentMachine = '';
            }
            else {
                modal.classList.toggle('hidden');
            }

        });
    }

    function actualizar(evt) {

        const resultado = TIPO_ACTUALIZAR[descripcion](numeroItem);
        if (!resultado) {
            components_ag = [];
            materials_milling_ag = [];
            materials_laser_ag = [];
            vinyls_ag = [];
            filaments_ag = [];
            resins_ag = [];
            threads_ag = [];

            costHoursArea = 0;
            costMinutesArea = 0;
            currentMachine = '';

            modal.classList.toggle('hidden');

            console.log(servicios_ag);

            guardar.removeEventListener('click', actualizar);
            closeModal.forEach(e => {
                e.removeEventListener('click', cerrarActualizar);
            });

            Toastify({
                text: "Servicio actualizado!",
                duration: 3000,
                style: {
                    background: '#10B981'
                }
            }).showToast();

            const btnDetails = document.querySelector('#' + numeroItem + ' ' + 'button')
            btnDetails.classList.remove('bg-red-500', 'active:bg-red-600', 'hover:bg-red-700');
            btnDetails.classList.add('bg-blue-500', 'active:bg-blue-600', 'hover:bg-blue-700');

            modal_content.classList.remove('max-h-96', 'overflow-auto')
        }

    }
}

function triggerChange(element) {
    let changeEvent = new Event('change');
    element.dispatchEvent(changeEvent);
}

let total;

function calcular() {
    // obtenemos todas las filas del tfoot
    let filas = document.querySelectorAll("#detalle_venta tr");

    total = manoObra.value != '' ? ((manoObra.value / 60) * 3.00) : 0.00;

    // recorremos cada una de las filas

    if(filas[0].outerText != 'Añadir servicios a la orden'){
        filas.forEach((e) => {

            // obtenemos las columnas de cada fila
            let columnas = e.querySelectorAll("td");
            // obtenemos los valores de la cantidad y importe
            let importe = parseFloat(columnas[3].textContent);

            total += importe;
        });
    }

    filas = document.querySelectorAll("#detalle_totales #total td");
    filas[2].textContent = total.toFixed(2);

}

function cambiarValue(elm, columna_total) {

    if (elm.value.trim().length != 0) {
        elm.classList.remove('border-red-300', 'focus:border-red-300', 'focus:ring-red-200');
        elm.classList.add('border-gray-300', 'focus:border-blue-300', 'focus:ring-blue-200');
    }
    elm.defaultValue = elm.value;
    columna_total.textContent = (elm.value * 1).toFixed(2);
    calcular();
}

function deleteServicio(id_tr) {

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

    html = document.querySelector("#detalle_venta").innerHTML;

    if (html.trim() == '') {
        document.querySelector("#detalle_venta").innerHTML = `
        <tr>
            <td class="p-3 text-center" colspan="6">
                <div class="flex flex-col gap-1 justify-center items-center text-base">
                    <span class="text-xl text-emerald-500">
                        <i class="fas fa-cart-arrow-down"></i>
                    </span>    
                    <p class="font-medium">Añadir servicios a la orden</p>
                </div>
            </td>
        </tr>`;
        document.querySelector("#detalle_totales").classList.add('hidden');
        document.querySelector("#acciones").classList.add('hidden');
        contandorF = 0;
    }
    else {
        calcular();
    }
}

let btn_anular = document.querySelector('#anular');

btn_anular.addEventListener('click', e => {
    location.reload();
});

/* Estructuras para componentes/materiales */

let components_ag = [];
let materials_milling_ag = [];
let materials_laser_ag = [];
let vinyls_ag = [];
let filaments_ag = [];
let resins_ag = [];
let threads_ag = [];

let costHoursArea = 0;
let costMinutesArea = 0;
let costPuntadas = 0;
let currentMachine = '';

// All controls
function calcularTable(){
    let total = 0.00;
    if(reason == 'M'){
        total = costHoursArea + CALCULATE_SUPPLIES[currentMachine]();
        if(currentMachine == 'Bordadora CNC'){
            total += costPuntadas;
        }
    }
    else{
        total = costMinutesArea + CALCULATE_SUPPLIES[currentMachine]();
        if(currentMachine == 'Bordadora CNC'){
            total += costPuntadas;
        }
    }

    document.querySelector('input[name="costo_base"]').value = total.toFixed(2);
} 

/* -------- Tabla para cada tipo de venta --------- */

function TablaElectronica(reason, id_servicio, numeroItem) {

    currentMachine = 'Electrónica';

    if (reason == 'M') {
        modal_content.innerHTML = `<main >
        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Horas en la estación</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="horas_area" placeholder="Ingrese la cantidad de horas" min="1" step="1" onchange="calcularHorasAreas(this, 'Electrónica')">
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>

        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Componentes</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 ui-autocomplete-input" placeholder="Ingrese el nombre del componente" name="documento" required="" type="text" id="searchComponent" autocomplete="off">
            <div class="rounded mt-2">
            <table class="w-full min-w-full divide-y divide-gray-200 text-sm text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                    <th class="p-3  font-medium uppercase tracking-wider w-1/2">Nombre</th>
                    <th class="p-3  font-medium uppercase tracking-wider w-1/4">Cantidad</th>
                    <th class="p-3  font-medium uppercase tracking-wider w-1/4">Precio</th>
                    <th class="p-3 font-medium uppercase tracking-wider w-1/4">Acción</th>
                    </tr>
                </thead>
                <tbody class="" id="lista-consumible">
                <tr class="bg-white border-b">
                <td class="p-3 text-center text-gray-900 whitespace-nowrap" colspan="6">
                    <div class="flex flex-col gap-1 justify-center items-center text-base">
                        <span class="text-xl text-emerald-500">
                            <i class="fas fa-cart-arrow-down"></i>
                        </span>
                        <p class="font-medium">Añadir componentes</p>
                    </div>
                </td>
                </tr>
                </tbody>
            </table>
        </div>
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>
        
        <label class="col-span-full text-sm block">
            <span class="text-gray-800 font-medium">Costo base</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="number" name="costo_base" value="0.00" disabled>
        </label>

        </main>`;

    }
    else {
        modal_content.innerHTML = `<main >
        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Componentes</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 ui-autocomplete-input" placeholder="Ingrese el nombre del componente" name="documento" required="" type="text" id="searchComponent" autocomplete="off">
            <div class="rounded mt-2">
            <table class="w-full min-w-full divide-y divide-gray-200 text-sm text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th class="p-3  font-medium uppercase tracking-wider w-1/3">Nombre</th>
                        <th class="p-3  font-medium uppercase tracking-wider w-1/4">Cantidad</th>
                        <th class="p-3  font-medium uppercase tracking-wider w-1/4">Precio</th>
                        <th class="p-3 font-medium uppercase tracking-wider w-1/4">Acción</th>
                    </tr>
                </thead>
                <tbody class="" id="lista-consumible">
                <tr class="bg-white border-b">
                <td class="p-3 text-center text-gray-900 whitespace-nowrap" colspan="6">
                    <div class="flex flex-col gap-1 justify-center items-center text-base">
                        <span class="text-xl text-emerald-500">
                            <i class="fas fa-cart-arrow-down"></i>
                        </span>
                        <p class="font-medium">Añadir componentes</p>
                    </div>
                </td>
                </tr>
                </tbody>
            </table>
        </div>
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>

        <label class="col-span-full text-sm block">
            <span class="text-gray-800 font-medium">Costo base</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="number" name="costo_base" value="0.00" disabled>
        </label>

        </main>`;

    }

    $("#searchComponent").autocomplete({

        source: function (request, response) {
            $.ajax({
                url: "../../ajax.php",
                type: 'post',
                dataType: "json",
                data: {
                    components: request.term,
                },
                success: function (data) {
                    if (!data.length) {
                        var result = { value: "0", label: "No se han encontrado resultados" };
                        data.push(result);
                    }
                    response(data)
                }
            });
        },
        delay: 500,
        minLength: 4,
        select: function (event, ui) {
            var value = ui.item.value;
            if (value == 0) {
                event.preventDefault();
            }
            else {
                ui.item.value = "";
                
                if(components_ag.findIndex((value) => value.component_id == ui.item.id) == -1){
                    htmlTable = `
                        <tr class="bg-white border-b">
                        <th  class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                            ${ui.item.label}
                        </th>
                        <td class="py-4 px-6">
                            ${ui.item.stock}/ <input type="number" class="text-sm w-4/6 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" id="cantidad" name="cantidad" value="1" min="1" max="${ui.item.stock}" onchange="cambiarTotalComponents(this,${ui.item.id}, ${ui.item.stock},${ui.item.price})">
                        </td>
                        <td class="py-4 px-6">
                            ${ui.item.price}
                        </td>
                        <td class="py-4 px-6">
                            <button
                            class="flex items-center justify-between px-2 py-2 text-xl font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray"
                            aria-label="Delete"
                            value="${ui.item.id}"
                            onclick="deleteComponent(this)"
                            >
                                <i class="fas fa-trash-alt text-base"></i>
                            </button>
                        </td>
                        </tr>
                    `;
                    
                    if (components_ag.length == 0) {
                        document.querySelector('#lista-consumible').innerHTML = htmlTable;
                    }
                    else {
                        document.querySelector('#lista-consumible').innerHTML += htmlTable;
                    }

                    components_ag.push({
                        component_id: ui.item.id,
                        cantidad: 1,
                        precio: ui.item.price
                    });

                    calcularTable();
                }
            }
        }
    
    });
}

//Funciones para tableElectronica

function calcularHorasAreas(e){ //maker
    costHoursArea = Number((e.value * PRICE_HOUR[currentMachine]));
    calcularTable();
}

function deleteComponent(e) {

    indice = components_ag.findIndex((value) => value.component_id == e.value) // obtenemos el indice
    components_ag.splice(indice, 1); // 1 es la cantidad de elemento a eliminar
    e.parentNode.parentNode.remove();

    calcularTable();
    html = document.querySelector('#lista-consumible').innerHTML;

    if (html.trim() == '') {
        document.querySelector('#lista-consumible').innerHTML = `
        <tr class="bg-white border-b">
            <td class="p-3 text-center text-gray-900 whitespace-nowrap" colspan="6">
                <div class="flex flex-col gap-1 justify-center items-center text-base">
                    <span class="text-xl text-emerald-500">
                        <i class="fas fa-cart-arrow-down"></i>
                    </span>    
                    <p class="font-medium">Añadir componentes</p>
                </div>
            </td>
        </tr>`;
    }
}

function cambiarTotalComponents(elm,id,max){
    let v = parseInt(elm.value);
    if (v < 1) elm.value = 1;
    if (v > max) elm.value = max;
    elm.defaultValue = elm.value;
    indiceComponent = components_ag.findIndex((value) => value.component_id == id)
    components_ag[indiceComponent].cantidad = elm.value;

    calcularTable();
}

/* ---------------Mini Milling-------------------- */

function TablaMiniFresadoraCNC(reason, id_servicio, numeroItem) {
    currentMachine = 'Mini Fresadora CNC';

    if (reason == 'M') {
        modal_content.innerHTML = `<main >
        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Horas de trabajo</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="horas_area" placeholder="Ingrese la cantidad de horas" min="1" step="1" onchange="calcularHorasAreas(this, 'Mini Fresadora CNC')">
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>

        <label class="text-sm block mb-5">
        <span class="text-gray-800 font-medium">Material</span>
        <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 ui-autocomplete-input" placeholder="Ingrese el nombre del material" name="documento" required="" type="text" id="searchMaterials" autocomplete="off">
        <div class="rounded mt-2">
        <table class="w-full min-w-full divide-y divide-gray-200 text-sm text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>
                    <th class="p-3  font-medium uppercase tracking-wider w-1/2">Nombre</th>
                    <th class="p-3  font-medium uppercase tracking-wider w-1/4">Precio</th>
                    <th class="p-3  font-medium uppercase tracking-wider w-1/4">Cantidad</th>
                    <th class="p-3 font-medium uppercase tracking-wider w-1/4">Acción</th>
                </tr>
            </thead>
            <tbody class="" id="lista-consumible">
            <tr class="bg-white border-b">
            <td class="p-3 text-center text-gray-900 whitespace-nowrap" colspan="6">
                <div class="flex flex-col gap-1 justify-center items-center text-base">
                    <span class="text-xl text-emerald-500">
                        <i class="fas fa-cart-arrow-down"></i>
                    </span>
                    <p class="font-medium">Añadir materiales</p>
                </div>
            </td>
            </tr>
            </tbody>
        </table>
    </div>
        <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
    </label>

        <label class="col-span-full text-sm block">
            <span class="text-gray-800 font-medium">Costo base</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="number" name="costo_base" value="0.00" disabled>
        </label>

        </main>`;
    }
    else {
        modal_content.innerHTML = `<main>
        <label class="text-sm block mb-5">
        <span class="text-gray-800 font-medium">Material</span>
        <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 ui-autocomplete-input" placeholder="Ingrese el nombre del material" name="documento" required="" type="text" id="searchMaterials" autocomplete="off">
        <div class="rounded mt-2">
        <table class="w-full min-w-full divide-y divide-gray-200 text-sm text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>
                    <th class="p-3  font-medium uppercase tracking-wider w-1/2">Nombre</th>
                    <th class="p-3  font-medium uppercase tracking-wider w-1/4">Precio</th>
                    <th class="p-3  font-medium uppercase tracking-wider w-1/4">Cantidad</th>
                    <th class="p-3 font-medium uppercase tracking-wider w-1/4">Acción</th>
                </tr>
            </thead>
            <tbody class="" id="lista-consumible">
            <tr class="bg-white border-b">
            <td class="p-3 text-center text-gray-900 whitespace-nowrap" colspan="6">
                <div class="flex flex-col gap-1 justify-center items-center text-base">
                    <span class="text-xl text-emerald-500">
                        <i class="fas fa-cart-arrow-down"></i>
                    </span>
                    <p class="font-medium">Añadir materiales</p>
                </div>
            </td>
            </tr>
            </tbody>
        </table>
    </div>
        <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
    </label>

        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Tiempo de fresado</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="minutos_area" placeholder="Ingrese la cantidad en minutos" min="1" step="1" onchange="calcularCostoOperacion(this, 'Mini Fresadora CNC')">
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>

        <label class="col-span-full text-sm block">
            <span class="text-gray-800 font-medium">Costo base</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="number" name="costo_base" value="0.00" disabled>
        </label>

        </main>`;

    }

    $("#searchMaterials").autocomplete({

        source: function (request, response) {
            $.ajax({
                url: "../../ajax.php",
                type: 'post',
                dataType: "json",
                data: {
                    materials_milling: request.term,
                },
                success: function (data) {
                    if (!data.length) {
                        var result = { value: "0", label: "No se han encontrado resultados" };
                        data.push(result);
                    }
                    response(data)
                }
            });
        },
        delay: 500,
        minLength: 4,
        select: function (event, ui) {
            var value = ui.item.value;
            if (value == 0) {
                event.preventDefault();
            }
            else {
                ui.item.value = "";
                
                if(materials_milling_ag.findIndex((value) => value.material_id == ui.item.id) == -1){
                     htmlTable = `
                        <tr class="bg-white border-b">
                        <th  class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                            ${ui.item.label}
                        </th>
                        <td class="py-4 px-6">
                            ${ui.item.stock}/ <input type="number" class="text-sm w-4/6 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" id="cantidad" name="cantidad" value="1" min="1" max="${ui.item.stock}" onchange="cambiarTotalMilling(this,${ui.item.id}, ${ui.item.stock},${ui.item.price})">
                        </td>
                        <td class="py-4 px-6">
                            ${ui.item.price}
                        </td>
                        <td class="py-4 px-6">
                            <button
                            class="flex items-center justify-between px-2 py-2 text-xl font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray"
                            aria-label="Delete"
                            value="${ui.item.id}"
                            onclick="deleteMaterialMilling(this)"
                            >
                                <i class="fas fa-trash-alt text-base"></i>
                            </button>
                        </td>
                        </tr>
                    `;
                    
                    if (materials_milling_ag.length == 0) {
                        document.querySelector('#lista-consumible').innerHTML = htmlTable;
                    }
                    else {
                        document.querySelector('#lista-consumible').innerHTML += htmlTable;
                    }

                    materials_milling_ag.push({
                        material_id: ui.item.id,
                        cantidad: 1,
                        precio: ui.item.price,
                    });

                    calcularTable();
                }
            }
        }
    
    });
}

function calcularCostoOperacion(e){ // Services
    const minutesToHours = e.value / 60;
    costMinutesArea =  Number((PRICE_MACHINE[currentMachine] / 4392) * minutesToHours);
    calcularTable();
    //operation costs = Printer price / required investment return time (h) * print time (h)
} 

function deleteMaterialMilling(e) {

    indice = materials_milling_ag.findIndex((value) => value.material_id == e.value) // obtenemos el indice
    materials_milling_ag.splice(indice, 1); // 1 es la cantidad de elemento a eliminar
    e.parentNode.parentNode.remove();

    calcularTable();
    html = document.querySelector('#lista-consumible').innerHTML;

    if (html.trim() == '') {
        document.querySelector('#lista-consumible').innerHTML = `
        <tr class="bg-white border-b">
            <td class="p-3 text-center text-gray-900 whitespace-nowrap" colspan="6">
                <div class="flex flex-col gap-1 justify-center items-center text-base">
                    <span class="text-xl text-emerald-500">
                        <i class="fas fa-cart-arrow-down"></i>
                    </span>    
                    <p class="font-medium">Añadir materiales</p>
                </div>
            </td>
        </tr>`;
    }
}

function cambiarTotalMilling(elm,id,max){
    let v = parseInt(elm.value);
    if (v < 1) elm.value = 1;
    if (v > max) elm.value = max;

    indiceMaterial = materials_milling_ag.findIndex((value) => value.material_id == id)
    materials_milling_ag[indiceMaterial].cantidad = elm.value;
    calcularTable();
}

/* ------------------LASER CNC---------------- */

function TablaLaserCNC(reason, id_servicio, numeroItem) {
    currentMachine = 'Láser CNC';
    if (reason == 'M') {
        modal_content.innerHTML = `<main>
        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Horas de trabajo</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="horas_area" placeholder="Ingrese la cantidad de horas" min="1" step="1" onchange="calcularHorasAreas(this, 'Láser CNC')">
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>

        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Material</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 ui-autocomplete-input" placeholder="Ingrese el nombre del material" name="documento" required="" type="text" id="searchMaterials" autocomplete="off">
            <div class="rounded mt-2">
            <table class="w-full min-w-full divide-y divide-gray-200 text-sm text-gray-500">
            <table class="w-full min-w-full divide-y divide-gray-200 text-sm text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
            <tr>
                <th class="p-3  font-medium uppercase tracking-wider w-1/2">Nombre</th>
                <th class="p-3  font-medium uppercase tracking-wider w-1/4">Precio x ft^2</th>
                <th class="p-3  font-medium uppercase tracking-wider w-1/4">W x H ft</th>
                <th class="p-3 font-medium uppercase tracking-wider w-1/4">Acción</th>
            </tr>
        </thead>
            <tbody class="" id="lista-consumible">
            <tr class="bg-white border-b">
            <td class="p-3 text-center text-gray-900 whitespace-nowrap" colspan="6">
                <div class="flex flex-col gap-1 justify-center items-center text-base">
                    <span class="text-xl text-emerald-500">
                        <i class="fas fa-cart-arrow-down"></i>
                    </span>
                    <p class="font-medium">Añadir materiales</p>
                </div>
            </td>
            </tr>
            </tbody>
        </table>
                </tbody>
            </table>
        </div>
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>

        <label class="col-span-full text-sm block">
            <span class="text-gray-800 font-medium">Costo base</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="number" name="costo_base" value="0.00" disabled>
        </label>

        </main>`;
    }
    else {
        modal_content.innerHTML = `<main>
        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Material</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 ui-autocomplete-input" placeholder="Ingrese el nombre del material" name="documento" required="" type="text" id="searchMaterials" autocomplete="off">
            <div class="rounded mt-2">
            <table class="w-full min-w-full divide-y divide-gray-200 text-sm text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th class="p-3  font-medium uppercase tracking-wider w-1/2">Nombre</th>
                        <th class="p-3  font-medium uppercase tracking-wider w-1/4">Precio x ft^2</th>
                        <th class="p-3  font-medium uppercase tracking-wider w-1/4">W x H ft</th>
                        <th class="p-3 font-medium uppercase tracking-wider w-1/4">Acción</th>
                    </tr>
                </thead>
                <tbody class="w-full" id="lista-consumible">
                <tr class="bg-white border-b">
                <td class="p-3 text-center text-gray-900 whitespace-nowrap" colspan="6">
                    <div class="flex flex-col gap-1 justify-center items-center text-base">
                        <span class="text-xl text-emerald-500">
                            <i class="fas fa-cart-arrow-down"></i>
                        </span>
                        <p class="font-medium">Añadir materiales</p>
                    </div>
                </td>
                </tr>
            </tbody>
            </table>
        </div>
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>

        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Tiempo de corte</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="minutos_area" placeholder="Ingrese la cantidad en minutos" min="0.00" step="0.01" onchange="calcularCostoOperacion(this, 'Láser CNC')">
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>

        <label class="col-span-full text-sm block">
            <span class="text-gray-800 font-medium">Costo base</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="number" name="costo_base" value="0.00" disabled>
        </label>

        </main>`;

    }

    $("#searchMaterials").autocomplete({

        source: function (request, response) {
            $.ajax({
                url: "../../ajax.php",
                type: 'post',
                dataType: "json",
                data: {
                    materials_laser: request.term,
                },
                success: function (data) {
                    if (!data.length) {
                        var result = { value: "0", label: "No se han encontrado resultados" };
                        data.push(result);
                    }
                    response(data)
                }
            });
        },
        delay: 500,
        minLength: 4,
        select: function (event, ui) {
            var value = ui.item.value;
            if (value == 0) {
                event.preventDefault();
            }
            else {
                ui.item.value = "";
                
                if(materials_laser_ag.findIndex((value) => value.material_id == ui.item.id) == -1){

                    if(reason == 'M'){
                        htmlTable = `
                        <tr class="bg-white border-b">
                            <th  class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                ${ui.item.label}
                            </th>
                            <td class="py-4 px-6">
                                ${ui.item.price}
                            </td>
                            <td class="py-4 px-6">
                                ${ui.item.width} x ${ui.item.height}
                            </td>
                            <td class="py-4 px-6">
                                <button
                                class="flex items-center justify-between px-2 py-2 text-xl font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray"
                                aria-label="Delete"
                                value="${ui.item.id}"
                                onclick="deleteMaterialLaser(this)"
                                >
                                    <i class="fas fa-trash-alt text-base"></i>
                                </button>
                            </td>
                            </tr>
                        </tr>
                        <tr class="bg-gray-100 border-b extend" id="${'container'+ui.item.id}">
                            <td class="py-4 px-6" colspan="4">
                            <div class="w-2/5 inline-block mr-20">
                                <span>Ancho (ft)</span><br>
                                <input class="mt-1 w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="width" placeholder="Ingrese la cantidad en pies" min="0.00" step="0.01" onchange="checkDimensionsLaser(this,${ui.item.id})">
                            </div>
                
                            <div class="w-2/5 inline-block">
                            <span>Alto (ft)</span>
                            <input class="mt-1 w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="height" placeholder="Ingrese la cantidad en pies" min="0.00" step="0.01" onchange="checkDimensionsLaser(this,${ui.item.id})">
                            </div>
                            </td>
                        </tr>
                        `;
                    }
                    else{
                        htmlTable = `
                        <tr class="bg-white border-b">
                            <th  class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                ${ui.item.label}
                            </th>
                            <td class="py-4 px-6">
                                ${ui.item.price}
                            </td>
                            <td class="py-4 px-6">
                                ${ui.item.width} x ${ui.item.height}
                            </td>
                            <td class="py-4 px-6">
                                <button
                                class="flex items-center justify-between px-2 py-2 text-xl font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray"
                                aria-label="Delete"
                                value="${ui.item.id}"
                                onclick="deleteMaterialLaser(this)"
                                >
                                    <i class="fas fa-trash-alt text-base"></i>
                                </button>
                            </td>
                            </tr>
                        </tr>
                        <tr class="bg-gray-100 border-b" id="${'container'+ui.item.id}">
                            <td class="p-3" colspan="4">
                            <div class="flex justify-evenly">
                            <label class="w-2/5 text-sm self-center">
                                <span>Cantidad</span><br>
                                <input class="p-2 mt-1 w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="cantidad" placeholder="Ingrese la cantidad de cortes" min="1" step="1" onchange="checkDimensionsLaser(this,${ui.item.id})">
                            </label>

                            <div class="w-2/5 text-sm">
                                <label class="block mb-3">
                                <span>Ancho (ft)</span><br>
                                <input class="p-2 mt-1 w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="width" placeholder="Ingrese la cantidad en pies" min="0.00" step="0.01" onchange="checkDimensionsLaser(this,${ui.item.id})">
                                </label>

                                <label class="block">
                                    <span>Alto (ft)</span>
                                    <input class="p-2 mt-1 w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="height" placeholder="Ingrese la cantidad en pies" min="0.00" step="0.01" onchange="checkDimensionsLaser(this,${ui.item.id})">
                                </label>
                            </div>
                            </div>
                            </td>
                        </tr>
                        `;

                    }

                    
                    if (materials_laser_ag.length == 0) {
                        document.querySelector('#lista-consumible').innerHTML = htmlTable;
                    }
                    else {
                        document.querySelector('#lista-consumible').innerHTML += htmlTable;
                    }

                    materials_laser_ag.push({
                        material_id: ui.item.id,
                        precio: ui.item.price,
                    });

                    calcularTable();
                }
            }
        }
    
    });
}

function checkDimensionsLaser(e,id){

    indiceMaterial = materials_laser_ag.findIndex((value) => value.material_id == id);

    if(reason == 'M'){
        if(document.querySelector('#container'+id+' input[name="width"]').value != "" && document.querySelector('#container'+id+' input[name="height"]').value != ""){
        
            materials_laser_ag[indiceMaterial].width = document.querySelector('#container'+id+' input[name="width"]').value;
            materials_laser_ag[indiceMaterial].height = document.querySelector('#container'+id+' input[name="height"]').value;
    
            calcularTable();
        }
    }else{
        if(document.querySelector('#container'+id+' input[name="width"]').value != "" && document.querySelector('#container'+id+' input[name="height"]').value != "" && document.querySelector('#container'+id+' input[name="cantidad"]').value != ""){
        
            materials_laser_ag[indiceMaterial].width = document.querySelector('#container'+id+' input[name="width"]').value;
            materials_laser_ag[indiceMaterial].height = document.querySelector('#container'+id+' input[name="height"]').value;
            materials_laser_ag[indiceMaterial].cantidad = document.querySelector('#container'+id+' input[name="cantidad"]').value;
    
            calcularTable();
        }
    }

}

function deleteMaterialLaser(e) {
    indice = materials_laser_ag.findIndex((value) => value.material_id == e.value) // obtenemos el indice
    materials_laser_ag.splice(indice, 1); // 1 es la cantidad de elemento a eliminar
    e.parentNode.parentNode.nextElementSibling.remove();
    e.parentNode.parentNode.remove();

    calcularTable();
    html = document.querySelector('#lista-consumible').innerHTML;

    if (html.trim() == '') {
        document.querySelector('#lista-consumible').innerHTML = `
        <tr class="bg-white border-b">
            <td class="p-3 text-center text-gray-900 whitespace-nowrap" colspan="6">
                <div class="flex flex-col gap-1 justify-center items-center text-base">
                    <span class="text-xl text-emerald-500">
                        <i class="fas fa-cart-arrow-down"></i>
                    </span>    
                    <p class="font-medium">Añadir materiales</p>
                </div>
            </td>
        </tr>`;
    }
}

/* ---------------- PLOTTER ----------------- */

function TablaCortadoraVinilo(reason, id_servicio, numeroItem) {

    currentMachine = 'Cortadora de Vinilo';

    if (reason == 'M') {
        modal_content.innerHTML = `<main>
        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Horas de trabajo</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="horas_area" placeholder="Ingrese la cantidad de horas" min="1" step="1" onchange="calcularHorasAreas(this, 'Cortadora de Vinilo')">
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>

        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Vinilos</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 ui-autocomplete-input" placeholder="Ingrese el nombre del vinilo" name="documento" required="" type="text" id="searchVinilos" autocomplete="off">
            <div class="rounded mt-2">
            <table class="w-full min-w-full divide-y divide-gray-200 text-sm text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th class="p-3  font-medium uppercase tracking-wider w-1/2">Nombre</th>
                        <th class="p-3  font-medium uppercase tracking-wider w-1/4">Precio x pulg^2</th>
                        <th class="p-3  font-medium uppercase tracking-wider w-1/4">w" x H ft</th>
                        <th class="p-3 font-medium uppercase tracking-wider w-1/4">Acción</th>
                    </tr>
                </thead>
                <tbody class="" id="lista-consumible">
                <tr class="bg-white border-b">
                <td class="p-3 text-center text-gray-900 whitespace-nowrap" colspan="6">
                    <div class="flex flex-col gap-1 justify-center items-center text-base">
                        <span class="text-xl text-emerald-500">
                            <i class="fas fa-cart-arrow-down"></i>
                        </span>
                        <p class="font-medium">Añadir vinilos</p>
                    </div>
                </td>
                </tr>
                </tbody>
            </table>
        </div>
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>

        <label class="col-span-full text-sm block">
            <span class="text-gray-800 font-medium">Costo base</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="number" name="costo_base" value="0.00" disabled>
        </label>

        </main>`;
    }
    else {
        modal_content.innerHTML = `<main>
        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Vinilos</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 ui-autocomplete-input" placeholder="Ingrese el nombre del vinilo" name="documento" required="" type="text" id="searchVinilos" autocomplete="off">
            <div class="rounded mt-2">
            <table class="w-full min-w-full divide-y divide-gray-200 text-sm text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th class="p-3  font-medium uppercase tracking-wider w-1/2">Nombre</th>
                        <th class="p-3  font-medium uppercase tracking-wider w-1/4">Precio x pulg^2</th>
                        <th class="p-3  font-medium uppercase tracking-wider w-1/4">W" x H ft</th>
                        <th class="p-3 font-medium uppercase tracking-wider w-1/4">Acción</th>
                    </tr>
                </thead>
                <tbody class="w-full" id="lista-consumible">
                <tr class="bg-white border-b">
                <td class="p-3 text-center text-gray-900 whitespace-nowrap" colspan="6">
                    <div class="flex flex-col gap-1 justify-center items-center text-base">
                        <span class="text-xl text-emerald-500">
                            <i class="fas fa-cart-arrow-down"></i>
                        </span>
                        <p class="font-medium">Añadir vinilos</p>
                    </div>
                </td>
                </tr>
            </tbody>
            </table>
        </div>
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>

        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Tiempo de corte</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="minutos_area" placeholder="Ingrese la cantidad en minutos" min="0.00" step="0.01" onchange="calcularCostoOperacion(this, 'Cortadora de Vinilo')">
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>

        <label class="col-span-full text-sm block">
            <span class="text-gray-800 font-medium">Costo base</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="number" name="costo_base" value="0.00" disabled>
        </label>

        </main>`;

    }

    $("#searchVinilos").autocomplete({

        source: function (request, response) {
            $.ajax({
                url: "../../ajax.php",
                type: 'post',
                dataType: "json",
                data: {
                    vinilos: request.term,
                },
                success: function (data) {
                    if (!data.length) {
                        var result = { value: "0", label: "No se han encontrado resultados" };
                        data.push(result);
                    }
                    response(data)
                }
            });
        },
        delay: 500,
        minLength: 4,
        select: function (event, ui) {
            var value = ui.item.value;
            if (value == 0) {
                event.preventDefault();
            }
            else {
                ui.item.value = "";
                
                if(vinyls_ag.findIndex((value) => value.vinilo_id == ui.item.id) == -1){
                     htmlTable = `
                    <tr class="bg-white border-b">
                        <th  class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                            ${ui.item.label}
                        </th>
                        <td class="py-4 px-6">
                        ${ui.item.price}
                        </td>
                        <td class="py-4 px-6">
                        ${ui.item.width} x ${ui.item.height}
                        </td>
                        <td class="py-4 px-6">
                            <button
                            class="flex items-center justify-between px-2 py-2 text-xl font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray"
                            aria-label="Delete"
                            value="${ui.item.id}"
                            onclick="deleteMaterialCutter(this)"
                            >
                                <i class="fas fa-trash-alt text-base"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="bg-gray-100 border-b extend" id="${'container'+ui.item.id}">
                        <td class="py-4 px-6" colspan="4">
                        <div class="w-2/5 inline-block mr-20">
                            <span>Ancho (")</span><br>
                            <input class="mt-1 w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="width" placeholder="Ingrese la cantidad en pulgadas" min="4" step="1" onchange="checkDimensionsCutter(this,${ui.item.id})">
                        </div>
            
                        <div class="w-2/5 inline-block">
                        <span>Alto (")</span>
                        <input class="mt-1 w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="height" placeholder="Ingrese la cantidad en pulgadas" min="4" step="1" onchange="checkDimensionsCutter(this,${ui.item.id})">
                        </div>
                        </td>
                    </tr>
                    `;
                    
                    if (vinyls_ag.length == 0) {
                        document.querySelector('#lista-consumible').innerHTML = htmlTable;
                    }
                    else {
                        document.querySelector('#lista-consumible').innerHTML += htmlTable;
                    }

                    vinyls_ag.push({
                        vinilo_id: ui.item.id,
                        precio: ui.item.price,
                    });

                    calcularTable();
                }
            }
        }
    
    });
}

function checkDimensionsCutter(e, id){

    if(e.value < 4){
        e.value = 4;
    }
    
    indiceMaterial = vinyls_ag.findIndex((value) => value.vinilo_id == id);

    if(document.querySelector('#container'+id+' input[name="width"]').value != "" && document.querySelector('#container'+id+' input[name="height"]').value != ""){
    
        vinyls_ag[indiceMaterial].width = document.querySelector('#container'+id+' input[name="width"]').value;
        vinyls_ag[indiceMaterial].height = document.querySelector('#container'+id+' input[name="height"]').value;

        calcularTable();
    }
}

function deleteMaterialCutter(e){
    indice = vinyls_ag.findIndex((value) => value.vinilo_id == e.value) // obtenemos el indice
    vinyls_ag.splice(indice, 1); // 1 es la cantidad de elemento a eliminar
    e.parentNode.parentNode.nextElementSibling.remove();
    e.parentNode.parentNode.remove();

    calcularTable();
    html = document.querySelector('#lista-consumible').innerHTML;

    if (html.trim() == '') {
        document.querySelector('#lista-consumible').innerHTML = `
        <tr class="bg-white border-b">
            <td class="p-3 text-center text-gray-900 whitespace-nowrap" colspan="6">
                <div class="flex flex-col gap-1 justify-center items-center text-base">
                    <span class="text-xl text-emerald-500">
                        <i class="fas fa-cart-arrow-down"></i>
                    </span>    
                    <p class="font-medium">Añadir vinilos</p>
                </div>
            </td>
        </tr>`;
    }
}

/* -------------3D Filamento --------------- */

function TablaImpresion3DEnFilamento(reason, id_servicio, numeroItem) {

    currentMachine = "Impresión 3D en filamento";

    if (reason == 'M') {
        modal_content.innerHTML = `<main>
        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Horas de trabajo</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="horas_area" placeholder="Ingrese la cantidad de horas" min="1" step="1" onchange="calcularHorasAreas(this, 'Impresión 3D en filamento')">
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>

        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Filamentos</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 ui-autocomplete-input" placeholder="Ingrese el nombre del filamento" name="documento" required="" type="text" id="searchFilaments" autocomplete="off">
            <div class="rounded mt-2">
            <table class="w-full min-w-full divide-y divide-gray-200 text-sm text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th class="p-3  font-medium uppercase tracking-wider w-1/3">Nombre</th>
                        <th class="p-3  font-medium uppercase tracking-wider w-1/4">Gramos</th>
                        <th class="p-3 font-medium uppercase tracking-wider w-1/4">Acción</th>
                    </tr>
                </thead>
                <tbody class="" id="lista-consumible">
                <tr class="bg-white border-b">
                <td class="p-3 text-center text-gray-900 whitespace-nowrap" colspan="6">
                    <div class="flex flex-col gap-1 justify-center items-center text-base">
                        <span class="text-xl text-emerald-500">
                            <i class="fas fa-cart-arrow-down"></i>
                        </span>
                        <p class="font-medium">Añadir filamentos</p>
                    </div>
                </td>
                </tr>
                </tbody>
            </table>
        </div>
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>

        <label class="col-span-full text-sm block">
            <span class="text-gray-800 font-medium">Costo base</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="number" name="costo_base" value="0.00" disabled>
        </label>

        </main>`;
    }
    else {
        modal_content.innerHTML = `<main>
        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Filamentos</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 ui-autocomplete-input" placeholder="Ingrese el nombre del filamento" name="documento" required="" type="text" id="searchFilaments" autocomplete="off">
            <div class="rounded mt-2">
            <table class="w-full min-w-full divide-y divide-gray-200 text-sm text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th class="p-3  font-medium uppercase tracking-wider w-1/3">Nombre</th>
                        <th class="p-3  font-medium uppercase tracking-wider w-1/4">Gramos</th>
                        <th class="p-3 font-medium uppercase tracking-wider w-1/4">Acción</th>
                    </tr>
                </thead>
                <tbody class="" id="lista-consumible">
                <tr class="bg-white border-b">
                <td class="p-3 text-center text-gray-900 whitespace-nowrap" colspan="6">
                    <div class="flex flex-col gap-1 justify-center items-center text-base">
                        <span class="text-xl text-emerald-500">
                            <i class="fas fa-cart-arrow-down"></i>
                        </span>
                        <p class="font-medium">Añadir filamentos</p>
                    </div>
                </td>
                </tr>
                </tbody>
            </table>
        </div>
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>

        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Peso del modelo (g)</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="peso_modelo" placeholder="Ingrese la cantidad en gramos" min="0.00" step="0.01" onchange="calcularMaterial(this)">
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>

        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Tiempo de impresión</span>
            <div class="mt-1 w-3/5 flex gap-2">
            <label for="hora" class="w-2/5">H:
            <input type="number" name="horas" class="w-4/6 text-sm p-1.5 m-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="0" onChange="tiempoImpresion(this)">
        </label>
        <label for="minutos" class="w-2/5">M:
            <input type="number" name="minutos" class="w-4/6 text-sm p-1.5 m-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" min="00" max="60" placeholder="0" onChange="tiempoImpresion(this)">
        </label>
            </div>
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>

        <label class="col-span-full text-sm block">
            <span class="text-gray-800 font-medium">Costo base</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="number" name="costo_base" value="0.00" disabled>
        </label>

        </main>`;

    }

    $("#searchFilaments").autocomplete({

        source: function (request, response) {
            $.ajax({
                url: "../../ajax.php",
                type: 'post',
                dataType: "json",
                data: {
                    filaments: request.term,
                },
                success: function (data) {
                    if (!data.length) {
                        var result = { value: "0", label: "No se han encontrado resultados" };
                        data.push(result);
                    }
                    response(data)
                }
            });
        },
        delay: 500,
        minLength: 4,
        select: function (event, ui) {
            var value = ui.item.value;
            if (value == 0) {
                event.preventDefault();
            }
            else {
                ui.item.value = "";
                
                if(filaments_ag.findIndex((value) => value.filament_id == ui.item.id) == -1){

                    if(reason == 'M'){
                        htmlTable = `
                       <tr class="bg-white border-b">
                           <th  class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                               ${ui.item.label}
                           </th>
                           <td class="py-4 px-6">
                           ${ui.item.current_weight}
                           </td>
                           <td class="py-4 px-6">
                               <button
                               class="flex items-center justify-between px-2 py-2 text-xl font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray"
                               aria-label="Delete"
                               value="${ui.item.id}"
                               onclick="deleteMaterialFilament(this)"
                               >
                                   <i class="fas fa-trash-alt text-base"></i>
                               </button>
                           </td>
                       </tr>
                       <tr class="bg-gray-100 border-b" id="${'container'+ui.item.id}">
                       <td class="py-4 px-6" colspan="4">
                           <label class="text-sm w-4/5 mx-auto flex items-center justify-evenly">
                           <span class="text-gray-800 font-medium">Cantidad de gramos</span>
                           <input class="mt-1 text-sm w-1/2 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="cantidad_gramos" placeholder="Ingrese la cantidad de gramos" min="0.00" step="0.01" onchange="calcularMaterial(this, ${ui.item.id})">
                           <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
                       </label>
                       </td>
                       </tr>
                       `;
                    }
                    else{
                        htmlTable = `
                        <tr class="bg-white border-b">
                            <th  class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                ${ui.item.label}
                            </th>
                            <td class="py-4 px-6">
                            ${ui.item.current_weight}
                            </td>
                            <td class="py-4 px-6">
                                <button
                                class="flex items-center justify-between px-2 py-2 text-xl font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray"
                                aria-label="Delete"
                                value="${ui.item.id}"
                                onclick="deleteMaterialFilament(this)"
                                >
                                    <i class="fas fa-trash-alt text-base"></i>
                                </button>
                            </td>
                        </tr>
                       `;
                    }
                    
                    if (filaments_ag.length == 0) {
                        document.querySelector('#lista-consumible').innerHTML = htmlTable;
                    }
                    else {
                        document.querySelector('#lista-consumible').innerHTML += htmlTable;
                    }

                    filaments_ag.push({
                        filament_id: ui.item.id,
                        precio: ui.item.price,
                        purchased_weight: ui.item.purchased_weight,
                    });

                    calcularTable();
                }
            }
        }
    
    });
}

function deleteMaterialFilament(e){
    indice = filaments_ag.findIndex((value) => value.filament_id == e.value) // obtenemos el indice
    filaments_ag.splice(indice, 1); // 1 es la cantidad de elemento a eliminar

    if(reason == 'M'){
        e.parentNode.parentNode.nextElementSibling.remove();
    }
    e.parentNode.parentNode.remove();

    calcularTable();
    html = document.querySelector('#lista-consumible').innerHTML;

    if (html.trim() == '') {
        document.querySelector('#lista-consumible').innerHTML = `
        <tr class="bg-white border-b">
            <td class="p-3 text-center text-gray-900 whitespace-nowrap" colspan="6">
                <div class="flex flex-col gap-1 justify-center items-center text-base">
                    <span class="text-xl text-emerald-500">
                        <i class="fas fa-cart-arrow-down"></i>
                    </span>    
                    <p class="font-medium">Añadir filamentos</p>
                </div>
            </td>
        </tr>`;
    }
}

/* Resina--------------------- */
function TablaImpresion3DEnResina(reason, id_servicio, numeroItem) {
    
    currentMachine = "Impresión 3D en resina";

    if (reason == 'M') {
        modal_content.innerHTML = `<main>
        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Horas de trabajo</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="horas_area" placeholder="Ingrese la cantidad de horas" min="1" step="1" onchange="calcularHorasAreas(this, 'Impresión 3D en resina')">
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>

        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Resinas</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 ui-autocomplete-input" placeholder="Ingrese el nombre de la resina" name="documento" required="" type="text" id="searchResins" autocomplete="off">
            <div class="rounded mt-2">
            <table class="w-full min-w-full divide-y divide-gray-200 text-sm text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th class="p-3  font-medium uppercase tracking-wider w-1/2">Nombre</th>
                        <th class="p-3  font-medium uppercase tracking-wider w-1/4">Precio</th>
                        <th class="p-3 font-medium uppercase tracking-wider w-1/4">Acción</th>
                    </tr>
                </thead>
                <tbody class="" id="lista-consumible">
                <tr class="bg-white border-b">
                <td class="p-3 text-center text-gray-900 whitespace-nowrap" colspan="6">
                    <div class="flex flex-col gap-1 justify-center items-center text-base">
                        <span class="text-xl text-emerald-500">
                            <i class="fas fa-cart-arrow-down"></i>
                        </span>
                        <p class="font-medium">Añadir resina</p>
                    </div>
                </td>
                </tr>
                </tbody>
            </table>
        </div>
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>

        <label class="col-span-full text-sm block">
            <span class="text-gray-800 font-medium">Costo base</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="number" name="costo_base" value="0.00" disabled>
        </label>

        </main>`;
    }
    else {
        modal_content.innerHTML = `<main>
        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Resinas</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 ui-autocomplete-input" placeholder="Ingrese el nombre de la resina" name="documento" required="" type="text" id="searchResins" autocomplete="off">
            <div class="rounded mt-2">
            <table class="w-full min-w-full divide-y divide-gray-200 text-sm text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th class="p-3  font-medium uppercase tracking-wider w-1/2">Nombre</th>
                        <th class="p-3  font-medium uppercase tracking-wider w-1/4">Precio</th>
                        <th class="p-3 font-medium uppercase tracking-wider w-1/4">Acción</th>
                    </tr>
                </thead>
                <tbody class="" id="lista-consumible">
                <tr class="bg-white border-b">
                <td class="p-3 text-center text-gray-900 whitespace-nowrap" colspan="6">
                    <div class="flex flex-col gap-1 justify-center items-center text-base">
                        <span class="text-xl text-emerald-500">
                            <i class="fas fa-cart-arrow-down"></i>
                        </span>
                        <p class="font-medium">Añadir resina</p>
                    </div>
                </td>
                </tr>
                </tbody>
            </table>
        </div>
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>

        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Peso del modelo (g)</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="peso_modelo" placeholder="Ingrese la cantidad en gramos" min="0.00" step="0.01" onchange="calcularMaterial(this)">
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>

        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Tiempo de impresión</span>
            <div class="mt-1 w-3/5 flex gap-2">
            <label for="horas" class="w-2/5">H:
            <input type="number" name="horas" class="w-4/6 text-sm p-1.5 m-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" min="1" placeholder="0" onChange="tiempoImpresion(this)">
        </label>
        <label for="minutos" class="w-2/5">M:
            <input type="number" name="minutos" class="w-4/6 text-sm p-1.5 m-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" min="00" max="60" placeholder="0" onChange="tiempoImpresion(this)">
        </label>
            </div>
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>

        <label class="col-span-full text-sm block">
            <span class="text-gray-800 font-medium">Costo base</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="number" name="costo_base" value="0.00" disabled>
        </label>

        </main>`;

    }

    $("#searchResins").autocomplete({

        source: function (request, response) {
            $.ajax({
                url: "../../ajax.php",
                type: 'post',
                dataType: "json",
                data: {
                    resins: request.term,
                },
                success: function (data) {
                    if (!data.length) {
                        var result = { value: "0", label: "No se han encontrado resultados" };
                        data.push(result);
                    }
                    response(data)
                }
            });
        },
        delay: 500,
        minLength: 4,
        select: function (event, ui) {
            var value = ui.item.value;
            if (value == 0) {
                event.preventDefault();
            }
            else {
                ui.item.value = "";
                
                if(resins_ag.length < 1){

                    if(reason == 'M'){
                        htmlTable = `
                       <tr class="bg-white border-b">
                           <th  class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                               ${ui.item.label}
                           </th>
                           <td class="py-4 px-6">
                           ${ui.item.current_weight}
                           </td>
                           <td class="py-4 px-6">
                               <button
                               class="flex items-center justify-between px-2 py-2 text-xl font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray"
                               aria-label="Delete"
                               value="${ui.item.id}"
                               onclick="deleteMaterialResin(this)"
                               >
                                   <i class="fas fa-trash-alt text-base"></i>
                               </button>
                           </td>
                       </tr>
                       <tr class="bg-gray-100 border-b" id="${'container'+ui.item.id}">
                       <td class="py-4 px-6" colspan="4">
                           <label class="text-sm w-4/5 mx-auto flex items-center justify-evenly">
                           <span class="text-gray-800 font-medium">Cantidad de gramos</span>
                           <input class="mt-1 text-sm w-1/2 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="cantidad_gramos" placeholder="Ingrese la cantidad de gramos" min="0.00" step="0.01" onchange="calcularMaterial(this, ${ui.item.id})">
                           <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
                       </label>
                       </td>
                       </tr>
                       `;
                    }
                    else{
                        htmlTable = `
                        <tr class="bg-white border-b">
                            <th  class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                ${ui.item.label}
                            </th>
                            <td class="py-4 px-6">
                            ${ui.item.current_weight}
                            </td>
                            <td class="py-4 px-6">
                                <button
                                class="flex items-center justify-between px-2 py-2 text-xl font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray"
                                aria-label="Delete"
                                value="${ui.item.id}"
                                onclick="deleteMaterialResin(this)"
                                >
                                    <i class="fas fa-trash-alt text-base"></i>
                                </button>
                            </td>
                        </tr>
                       `;
                    }
                    
                    if (resins_ag.length == 0) {
                        document.querySelector('#lista-consumible').innerHTML = htmlTable;
                    }
                    else {
                        document.querySelector('#lista-consumible').innerHTML += htmlTable;
                    }

                    resins_ag.push({
                        resin_id: ui.item.id,
                        precio: ui.item.price,
                        purchased_weight: ui.item.purchased_weight,
                    });

                    calcularTable();
                }
            }
        }
    
    });
}

function calcularMaterial(e,id=null){

    if(currentMachine == 'Impresión 3D en filamento'){

        indiceMaterial = filaments_ag.findIndex((value) => value.filament_id == id);
        if(reason == 'M')  {
            if(document.querySelector('#container'+id+' input[name="cantidad_gramos"]').value != "" ){
        
                filaments_ag[indiceMaterial].cantidad = document.querySelector('#container'+id+' input[name="cantidad_gramos"]').value;
            }
            else{
                filaments_ag[indiceMaterial].cantidad = 0;
            }
        }
        else{
            if(filaments_ag.length > 1){
                const cantidad = e.value / filaments_ag.length;
                filaments_ag.forEach(filament => {
                    filament.cantidad = cantidad;
                });
            }
            else{
                filaments_ag[0].cantidad = e.value;
            }
        }
    }
    else{
        
        indiceMaterial = resins_ag.findIndex((value) => value.resin_id == id);

        if(reason == 'M')  {
            if(document.querySelector('#container'+id+' input[name="cantidad_gramos"]').value != "" ){
        
                resins_ag[indiceMaterial].cantidad = document.querySelector('#container'+id+' input[name="cantidad_gramos"]').value;
            }
            else{
                resins_ag[indiceMaterial].cantidad = 0;
            }
        }
        else{
            resins_ag[0].cantidad = e.value;           
        }
    }

    calcularTable();
}

function tiempoImpresion(e){

    if(document.querySelector('input[name="horas"]').value != "" && document.querySelector('input[name="minutos"]').value != ""){
        const tiempo = Number(document.querySelector('input[name="horas"]').value) + Number(document.querySelector('input[name="minutos"]').value / 60);
        costMinutesArea =  Number((PRICE_MACHINE[currentMachine] / 4392) * tiempo);

        calcularTable();
    }
    else{
        costMinutesArea =  0
        calcularTable();
    }
}

function deleteMaterialResin(e){
    indice = resins_ag.findIndex((value) => value.resin_id == e.value) // obtenemos el indice
    resins_ag.splice(indice, 1); // 1 es la cantidad de elemento a eliminar

    if(reason == 'M'){
        e.parentNode.parentNode.nextElementSibling.remove();
    }
    e.parentNode.parentNode.remove();

    calcularTable();
    html = document.querySelector('#lista-consumible').innerHTML;

    if (html.trim() == '') {
        document.querySelector('#lista-consumible').innerHTML = `
        <tr class="bg-white border-b">
            <td class="p-3 text-center text-gray-900 whitespace-nowrap" colspan="6">
                <div class="flex flex-col gap-1 justify-center items-center text-base">
                    <span class="text-xl text-emerald-500">
                        <i class="fas fa-cart-arrow-down"></i>
                    </span>    
                    <p class="font-medium">Añadir filamentos</p>
                </div>
            </td>
        </tr>`;
    }
}

/* Software ----------------------- */

let selectSoftware;
function TablaSoftwareDeDiseño(reason, id_servicio, numeroItem) {

    currentMachine = "Impresión 3D en resina";

    modal_content.innerHTML = `<main >
    <label class="text-sm block mb-5">
        <span class="text-gray-800 font-medium">Horas de trabajo</span>
        <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="horas_area" placeholder="Ingrese la cantidad de horas" min="1" step="1" onchange="cambioPrecioSoftware(this)">
        <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
    </label>

    <label class="text-sm block mb-5">
    <span class="text-gray-800 font-medium">Seleccione el software</span>
    <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" name="software" onchange="cambioPrecioSoftware(this)">
    </select>
    </label>

    <label class="col-span-full text-sm block">
    <span class="text-gray-800 font-medium">Costo base</span>
    <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="number" name="costo_base" value="0.00" disabled>
    </label>

    </main>`;

    selectSoftware = document.querySelector('select[name="software"]');
    $.ajax({
        url: "../../ajax.php",
        type: 'post',
        dataType: 'json',
        data: {
            softwares: 'getSoftware',
        },
        success: function (data) {
            data.forEach(software => {
                selectSoftware.innerHTML += `<option value="${software.software_id}" class="price-${software.price}">${software.name} - ${software.price}</option>`;
            });

        }
    });
}

function cambioPrecioSoftware(e) {
    if(document.querySelector('input[name="horas_area"]').value != ""){
        const tiempo = Number(document.querySelector('input[name="horas_area"]').value);
        document.querySelector('input[name="costo_base"]').value = Number(selectSoftware.options[selectSoftware.selectedIndex].className.split('-')[1]) * tiempo;

    }
    else{
        document.querySelector('input[name="costo_base"]').value = 0;
    }

}

/* ----------Bordadora */

function TablaBordadoraCNC(reason, id_servicio, numeroItem) {

    currentMachine = "Bordadora CNC";

    if (reason == 'M') {
        modal_content.innerHTML = `<main>
        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Horas de trabajo</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="horas_area" placeholder="Ingrese la cantidad de minutos" min="1" step="1" onchange="calcularHorasAreas(this, 'Bordadora CNC')">
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>

        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Hilos</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 ui-autocomplete-input" placeholder="Ingrese el nombre del hilo" name="documento" required="" type="text" id="searchThreads" autocomplete="off">
            <div class="rounded mt-2">
            <table class="w-full min-w-full divide-y divide-gray-200 text-sm text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th class="p-3  font-medium uppercase tracking-wider w-1/2">Nombre</th>
                        <th class="p-3  font-medium uppercase tracking-wider w-1/4">Cantidad</th>
                        <th class="p-3 font-medium uppercase tracking-wider w-1/4">Acción</th>
                    </tr>
                </thead>
                <tbody class="" id="lista-consumible">
                <tr class="bg-white border-b">
                <td class="p-3 text-center text-gray-900 whitespace-nowrap" colspan="6">
                    <div class="flex flex-col gap-1 justify-center items-center text-base">
                        <span class="text-xl text-emerald-500">
                            <i class="fas fa-cart-arrow-down"></i>
                        </span>
                        <p class="font-medium">Añadir hilos</p>
                    </div>
                </td>
                </tr>
                </tbody>
            </table>
        </div>
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>

        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Cantidad de puntadas</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="cantidad_puntadas" placeholder="Ingrese la cantidad de puntadas" min="0.00" step="0.01"  onchange="cambioPuntadas(this)">
        </label>

        <label class="col-span-full text-sm block">
            <span class="text-gray-800 font-medium">Costo base</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="number" name="costo_base" value="0.00" disabled>
        </label>

        </main>`;
    }
    else {
        modal_content.innerHTML = `<main>
        <label class="text-sm block mb-5">
        <span class="text-gray-800 font-medium">Hilos</span>
        <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 ui-autocomplete-input" placeholder="Ingrese el nombre del hilo" name="documento" required="" type="text" id="searchThreads" autocomplete="off">
        <div class="rounded mt-2">
        <table class="w-full min-w-full divide-y divide-gray-200 text-sm text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>
                    <th class="p-3  font-medium uppercase tracking-wider w-1/2">Nombre</th>
                    <th class="p-3  font-medium uppercase tracking-wider w-1/4">Cantidad</th>
                    <th class="p-3 font-medium uppercase tracking-wider w-1/4">Acción</th>
                </tr>
            </thead>
            <tbody class="" id="lista-consumible">
            <tr class="bg-white border-b">
            <td class="p-3 text-center text-gray-900 whitespace-nowrap" colspan="6">
                <div class="flex flex-col gap-1 justify-center items-center text-base">
                    <span class="text-xl text-emerald-500">
                        <i class="fas fa-cart-arrow-down"></i>
                    </span>
                    <p class="font-medium">Añadir hilos</p>
                </div>
            </td>
            </tr>
        </tbody>
        </table>
    </div>
        <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
    </label>

        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Cantidad de puntadas</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="cantidad_puntadas" placeholder="Ingrese la cantidad de puntadas" min="0.00" step="0.01" onchange="cambioPuntadas(this)">
        </label>

        <label class="text-sm block mb-5">
            <span class="text-gray-800 font-medium">Tiempo de bordado</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" name="minutos_area" placeholder="Ingrese la cantidad en minutos" min="0.00" step="0.01" onchange="calcularCostoOperacion(this, 'Bordadora CNC')">
            <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
        </label>

        <label class="col-span-full text-sm block">
            <span class="text-gray-800 font-medium">Costo base</span>
            <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="number" name="costo_base" value="0.00" disabled>
        </label>

        </main>`;

    }

    $("#searchThreads").autocomplete({

        source: function (request, response) {
            $.ajax({
                url: "../../ajax.php",
                type: 'post',
                dataType: "json",
                data: {
                    threads: request.term,
                },
                success: function (data) {
                    if (!data.length) {
                        var result = { value: "0", label: "No se han encontrado resultados" };
                        data.push(result);
                    }
                    response(data)
                }
            });
        },
        delay: 500,
        minLength: 4,
        select: function (event, ui) {
            var value = ui.item.value;
            if (value == 0) {
                event.preventDefault();
            }
            else {
                ui.item.value = "";
                if(threads_ag.findIndex((value) => value.material_id == ui.item.id) == -1){
                     htmlTable = `
                        <tr class="bg-white border-b">
                        <th  class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                            ${ui.item.label}
                        </th>
                        <td class="py-4 px-6">
                            ${ui.item.current_amount}
                        </td>
                        <td class="py-4 px-6">
                            <button
                            class="flex items-center justify-between px-2 py-2 text-xl font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray"
                            aria-label="Delete"
                            value="${ui.item.id}"
                            onclick="deleteThread(this)"
                            >
                                <i class="fas fa-trash-alt text-base"></i>
                            </button>
                        </td>
                        </tr>
                    `;
                    
                    if (threads_ag.length == 0) {
                        document.querySelector('#lista-consumible').innerHTML = htmlTable;
                    }
                    else {
                        document.querySelector('#lista-consumible').innerHTML += htmlTable;
                    }

                    threads_ag.push({
                        thread_id: ui.item.id,
                    });

                    calcularTable();
                }
            }
        }
    
    });
}

function cambioPuntadas(e){
    //every 1000 stitches one dollar
    let puntadas = e.value;
    costPuntadas = puntadas / 1000;

    const cantidad = puntadas / threads_ag.length;
    threads_ag.forEach((value) => {
        value.cantidad = cantidad;
    });

    calcularTable();
}

function deleteThread(e){
    indice = threads_ag.findIndex((value) => value.resin_id == e.value) // obtenemos el indice
    threads_ag.splice(indice, 1); // 1 es la cantidad de elemento a eliminar

    e.parentNode.parentNode.remove();

    calcularTable();
    html = document.querySelector('#lista-consumible').innerHTML;

    if (html.trim() == '') {
        document.querySelector('#lista-consumible').innerHTML = `
        <tr class="bg-white border-b">
            <td class="p-3 text-center text-gray-900 whitespace-nowrap" colspan="6">
                <div class="flex flex-col gap-1 justify-center items-center text-base">
                    <span class="text-xl text-emerald-500">
                        <i class="fas fa-cart-arrow-down"></i>
                    </span>    
                    <p class="font-medium">Añadir hilos</p>
                </div>
            </td>
        </tr>`;
    }
}

/* -------- Save para cada tipo de venta --------- */

//Debes convertir las horas a minutos para almacenarlo en la BD -> Maker
function saveElectronica(numeroItem){

    let errores = {};

    if(reason == 'M'){
        //Validaciones

        if (Object.keys(errores).length > 0) {

            return true;
        }
        else {
            servicios_ag[indice].detalles = {
                horas_area: document.querySelector('input[name="horas_area"]').value * 60,
                components: components_ag,
                costo_base: document.querySelector('input[name="costo_base"]').value,
            }

            const precio = document.querySelector('#' + numeroItem + ' .precio');
            precio.value = document.querySelector('input[name="costo_base"]').value;
    
            triggerChange(precio);
    
            return false;
        }
    }
    else {
        //Validaciones

        if (Object.keys(errores).length > 0) {

            return true;
        }
        else {
            servicios_ag[indice].detalles = {
                components: components_ag,
                costo_base: document.querySelector('input[name="costo_base"]').value,
            }

            const precio = document.querySelector('#' + numeroItem + ' .precio');
            precio.value = document.querySelector('input[name="costo_base"]').value;
            
            triggerChange(precio);
    
            return false;
        }

        
    }

}

function saveMiniFresadoraCNC(numeroItem){

    let errores = {};

    if(reason == 'M'){
        //Validaciones

        if (Object.keys(errores).length > 0) {

            return true;
        }
        else {
            servicios_ag[indice].detalles = {
                horas_area: document.querySelector('input[name="horas_area"]').value * 60,
                materials:materials_milling_ag,
                costo_base: document.querySelector('input[name="costo_base"]').value,
            }

            const precio = document.querySelector('#' + numeroItem + ' .precio');
            precio.value = document.querySelector('input[name="costo_base"]').value;
    
            triggerChange(precio);
    
            return false;
        }
    }
    else {
        //Validaciones

        if (Object.keys(errores).length > 0) {

            return true;
        }
        else {
            servicios_ag[indice].detalles = {
                minutos_area: document.querySelector('input[name="minutos_area"]').value,
                materials:materials_milling_ag,
                costo_base: document.querySelector('input[name="costo_base"]').value,
            }

            const precio = document.querySelector('#' + numeroItem + ' .precio');
            precio.value = document.querySelector('input[name="costo_base"]').value;
            
            triggerChange(precio);
    
            return false;
        }

    }
}

function saveLaserCNC(numeroItem){

    let errores = {};
    
    if(reason == 'M'){
        if (Object.keys(errores).length > 0) {

            return true;
        }
        else {
            servicios_ag[indice].detalles = {
                horas_area: document.querySelector('input[name="horas_area"]').value * 60,
                materials: materials_laser_ag,
                costo_base: document.querySelector('input[name="costo_base"]').value,
            }

            const precio = document.querySelector('#' + numeroItem + ' .precio');
            precio.value = document.querySelector('input[name="costo_base"]').value;
    
            triggerChange(precio);
    
            return false;
        }
    }
    else {
        if (Object.keys(errores).length > 0) {

            return true;
        }
        else {
            servicios_ag[indice].detalles = {
                minutos_area: document.querySelector('input[name="minutos_area"]').value,
                materials: materials_laser_ag,
                costo_base: document.querySelector('input[name="costo_base"]').value,
            }

            const precio = document.querySelector('#' + numeroItem + ' .precio');
            precio.value = document.querySelector('input[name="costo_base"]').value;
            
            triggerChange(precio);
    
            return false;
        }
    }
}

function saveCortadoraVinilo(numeroItem){

    let errores = {};
    
    if(reason == 'M'){
        if (Object.keys(errores).length > 0) {

            return true;
        }
        else {
            servicios_ag[indice].detalles = {
                horas_area: document.querySelector('input[name="horas_area"]').value * 60,
                vinilos: vinyls_ag,
                costo_base: document.querySelector('input[name="costo_base"]').value,
            }

            const precio = document.querySelector('#' + numeroItem + ' .precio');
            precio.value = document.querySelector('input[name="costo_base"]').value;
    
            triggerChange(precio);
    
            return false;
        }
    }
    else {
        if (Object.keys(errores).length > 0) {

            return true;
        }
        else {
            servicios_ag[indice].detalles = {
                minutos_area: document.querySelector('input[name="minutos_area"]').value,
                materials: vinyls_ag,
                costo_base: document.querySelector('input[name="costo_base"]').value,
            }

            const precio = document.querySelector('#' + numeroItem + ' .precio');
            precio.value = document.querySelector('input[name="costo_base"]').value;
            
            triggerChange(precio);
    
            return false;
        }
    }
}

function saveImpresion3DEnFilamento(numeroItem){

    let errores = {};
    
    if(reason == 'M'){
        if (Object.keys(errores).length > 0) {

            return true;
        }
        else {
            servicios_ag[indice].detalles = {
                horas_area: document.querySelector('input[name="horas_area"]').value * 60,
                filaments: filaments_ag,
                costo_base: document.querySelector('input[name="costo_base"]').value,
            }

            const precio = document.querySelector('#' + numeroItem + ' .precio');
            precio.value = document.querySelector('input[name="costo_base"]').value;
    
            triggerChange(precio);
    
            return false;
        }
    }
    else {
        if (Object.keys(errores).length > 0) {

            return true;
        }
        else {
            servicios_ag[indice].detalles = {
                minutos_area: (document.querySelector('input[name="horas"]').value * 60) + document.querySelector('input[name="minutos"]').value,
                filaments: filaments_ag,
                costo_base: document.querySelector('input[name="costo_base"]').value,
            }

            const precio = document.querySelector('#' + numeroItem + ' .precio');
            precio.value = document.querySelector('input[name="costo_base"]').value;
            
            triggerChange(precio);
    
            return false;
        }
    }
}

function saveImpresion3DEnResina(numeroItem){

    let errores = {};
    
    if(reason == 'M'){
        if (Object.keys(errores).length > 0) {

            return true;
        }
        else {
            servicios_ag[indice].detalles = {
                horas_area: document.querySelector('input[name="horas_area"]').value * 60,
                resins: resins_ag,
                costo_base: document.querySelector('input[name="costo_base"]').value,
            }

            const precio = document.querySelector('#' + numeroItem + ' .precio');
            precio.value = document.querySelector('input[name="costo_base"]').value;
    
            triggerChange(precio);
    
            return false;
        }
    }
    else {
        if (Object.keys(errores).length > 0) {

            return true;
        }
        else {
            servicios_ag[indice].detalles = {
                minutos_area: Number(document.querySelector('input[name="horas"]').value * 60) + Number(document.querySelector('input[name="minutos"]').value),
                resins: resins_ag,
                costo_base: document.querySelector('input[name="costo_base"]').value,
            }

            const precio = document.querySelector('#' + numeroItem + ' .precio');
            precio.value = document.querySelector('input[name="costo_base"]').value;
            
            triggerChange(precio);
    
            return false;
        }
    }
}

function saveSoftwareDeDiseño(numeroItem){

    let errores = {};

    //Validaciones
    
    if (Object.keys(errores).length > 0) {

        return true;
    }
    else {
        servicios_ag[indice].detalles = {
            horas_area: document.querySelector('input[name="horas_area"]').value * 60,
            software: selectSoftware.value,
            costo_base: document.querySelector('input[name="costo_base"]').value,
        }

        const precio = document.querySelector('#' + numeroItem + ' .precio');
        precio.value = document.querySelector('input[name="costo_base"]').value;

        triggerChange(precio);

        return false;
    }
    
}

function saveBordadoraCNC (numeroItem){

    let errores = {};
    
    if(reason == 'M'){
        //Validaciones

        if (Object.keys(errores).length > 0) {

            return true;
        }
        else {
            servicios_ag[indice].detalles = {
                horas_area: document.querySelector('input[name="horas_area"]').value * 60,
                threads: threads_ag,
                costo_base: document.querySelector('input[name="costo_base"]').value,
            }

            const precio = document.querySelector('#' + numeroItem + ' .precio');
            precio.value = document.querySelector('input[name="costo_base"]').value;
    
            triggerChange(precio);
    
            return false;
        }
    }
    else {
        //Validaciones

        if (Object.keys(errores).length > 0) {

            return true;
        }
        else {
            servicios_ag[indice].detalles = {
                minutos_area: document.querySelector('input[name="minutos_area"]').value,
                threads: threads_ag,
                costo_base: document.querySelector('input[name="costo_base"]').value,
            }

            const precio = document.querySelector('#' + numeroItem + ' .precio');
            precio.value = document.querySelector('input[name="costo_base"]').value;
            
            triggerChange(precio);
    
            return false;
        }

        
    }
}

/* -------------Calculate supplies ---------------------- */

function calculateSuppliesElectronica(){
    let suma = 0.00;
    components_ag.forEach(component => {
        suma += component.cantidad * component.precio;
    });
    return suma;
}

function calculateSuppliesMiniFresadoraCNC(){
    let suma = 0.00;
    materials_milling_ag.forEach(material => {
        suma += material.cantidad * material.precio;
    });
    return suma;
}

function calculateSuppliesLaserCNC(){
    let suma = 0.00;
    materials_laser_ag.forEach(material => {

        if(reason == 'M'){
            if(material.hasOwnProperty('width') && material.hasOwnProperty('height')){
                suma += material.precio * (material.width * material.height);
            }
            else{
                suma += 0.00;
            }
        }
        else {
            if(material.hasOwnProperty('width') && material.hasOwnProperty('height') && material.hasOwnProperty('cantidad')){
                suma += material.precio * (material.width * material.height) * material.cantidad;
            }
            else{
                suma += 0.00;
            }
        }

    });
    return suma;
}

function calculateSuppliesCortadoraVinilo(){
    let suma = 0.00;
    vinyls_ag.forEach(material => {
        if(material.hasOwnProperty('width') && material.hasOwnProperty('height')){
            suma += material.precio * (material.width * material.height);
        }
        else{
            suma += 0.00;
        }
    });
    return suma;
}

function calculateSuppliesImpresion3DEnFilamento(){
    let suma = 0.00;
    filaments_ag.forEach(filament => {
        if(filament.hasOwnProperty('cantidad') && filament.cantidad > 0){
            suma += (filament.precio/filament.purchased_weight) * filament.cantidad;
            //precio del filamento/peso del filamento (g) * peso del modelo (g)
        }
        else{
            suma += 0.00;
        }
    });
    return suma;
}

function calculateSuppliesImpresion3DEnResina(){
    let suma = 0.00;
    resins_ag.forEach(resin => {
        if(resin.hasOwnProperty('cantidad') && resin.cantidad > 0){
            suma += (resin.precio/resin.purchased_weight) * resin.cantidad;
            //precio del resin/peso del resin (g) * peso del modelo (g)
        }
        else{
            suma += 0.00;
        }
    });
    return suma;
}

function calculateSuppliesBordadoraCNC(){
    return 0.00;
}