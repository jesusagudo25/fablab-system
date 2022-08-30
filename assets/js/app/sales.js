const tipoDocumento = document.querySelector('select[name="tipodocumento"]'),
    tituloDocumento = document.querySelector('#tituloDocumento'),
    inputDocumento = document.querySelector('input[name="documento"]'),
    nombreUsuario = document.querySelector('input[name="name"]'),
    idHidden = document.querySelector('input[type="hidden"]'),
    accion = document.querySelector('#action'),
    agregar = document.querySelector('#agregar'),
    categoria_servicio = document.querySelector('select[name="categoria_servicio"]'),
    servicio = document.querySelector('select[name="servicio"]'),
    nombreCliente = document.querySelector('input[name="name"]'),
    closeModal = document.querySelectorAll('.close'),
    modal = document.querySelector('#modal'),
    guardar = document.querySelector('button[name="guardar"]'),
    modal_content = document.querySelector('#modal-content'),
    observacion = document.querySelector('textarea[name="observation"]'),
    registrar = document.querySelector('button[type="submit"]'),
    email = document.querySelector('input[name="email"]'),
    telefono = document.querySelector('input[name="telefono"]'),
    edad = document.querySelectorAll('input[name="edad"]'),
    sexo = document.querySelectorAll('input[name="sexo"]'),
    provincia = document.querySelector('select[name="provincia"]'),
    distrito = document.querySelector('select[name="distrito"]'),
    corregimiento = document.querySelector('select[name="corregimiento"]'),
    formulario = document.querySelector('form');

//Informacion inicial
let distritos = [];
let corregimientos = [];
let events = [];
let typeSaleValue = 'M';

//Obtener los eventos
fetch('./functions.php', {
    method: "POST",
    mode: "same-origin",
    credentials: "same-origin",
    headers: {
        "Content-Type": "application/json"
    },
    body: JSON.stringify({
        datos: {
            solicitud: "evt",
        }
    })
})
    .then(res => res.json())
    .then(data => {
        events = data;
    });

//Provincias-Distritos-Corregimientos
let formDataDistricts = new FormData();
formDataDistricts.append('solicitud', 'd');
fetch('./functions.php', {
    method: "POST",
    body: formDataDistricts
})
    .then(res => res.json())
    .then(data => {
        distritos = data;

        const resul = distritos.filter(x => x.province_id == provincia.value);
        resul.forEach(e => {
            if (e.name == 'Santiago') {
                distrito.innerHTML += `<option value="${e.district_id}" selected>${e.name}</option>`;
            }
            else {
                distrito.innerHTML += `<option value="${e.district_id}" >${e.name}</option>`;
            }
        });

        let formDataTownships = new FormData();
        formDataTownships.append('solicitud', 'c');

        fetch('./functions.php', {
            method: "POST",
            body: formDataTownships
        })
            .then(res => res.json())
            .then(data => {
                corregimientos = data;

                const resul = corregimientos.filter(x => x.district_id == distrito.value);
                resul.forEach(e => {
                    if (e.name == 'Santiago') {
                        corregimiento.innerHTML += `<option value="${e.township_id}" selected>${e.name}</option>`;
                    }
                    else {
                        corregimiento.innerHTML += `<option value="${e.township_id}">${e.name}</option>`;
                    }
                });
            });
    });

//Algoritmo Provincia-Distrito-Corregimiento
provincia.addEventListener('change', evt => {

    distrito.innerHTML = '';
    let resul = distritos.filter(x => x.province_id == provincia.value);
    resul.forEach(e => {
        distrito.innerHTML += `<option value="${e.district_id}">${e.name}</option>`;
    });

    corregimiento.innerHTML = '';
    resul = corregimientos.filter(x => x.district_id == distrito.value);
    resul.forEach(e => {
        corregimiento.innerHTML += `<option value="${e.township_id}">${e.name}</option>`;
    });

});


distrito.addEventListener('change', evt => {
    corregimiento.innerHTML = '';
    const resul = corregimientos.filter(x => x.district_id == distrito.value);
    resul.forEach(e => {
        corregimiento.innerHTML += `<option value="${e.township_id}">${e.name}</option>`;
    });
});

//Obtener los servicios
let servicios = [];

fetch('./functions.php', {
    method: "POST",
    mode: "same-origin",
    credentials: "same-origin",
    headers: {
        "Content-Type": "application/json"
    },
    body: JSON.stringify({
        datos: {
            solicitud: "s",
        }
    })
})
    .then(res => res.json())
    .then(data => {
        servicios = data;
    });

//Cambio de categoria servicio
categoria_servicio.addEventListener('change', evt => {

    servicio.innerHTML = '';
    servicios[categoria_servicio.value].forEach((e) => {
        servicio.innerHTML += `<option value="${e.id}" >${e.name}</option>`;
    });

});

tipoDocumento.addEventListener('change', evt => {
    inputDocumento.value = '';
    triggerKeyup(inputDocumento)

});

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
            nombreUsuario.value = ui.item.name;
            nombreUsuario.disabled = true;
            nombreUsuario.classList.add('bg-gray-300');
            nombreUsuario.classList.add('cursor-not-allowed');

            if (ui.item.email) {
                email.value = ui.item.email;
            }
            else {
                email.placeholder = 'Sin correo electrónico asignado';
            }

            email.disabled = true;
            email.classList.add('bg-gray-300');
            email.classList.add('cursor-not-allowed');

            if (ui.item.telephone) {
                telefono.value = ui.item.telephone;
            }
            else {
                telefono.placeholder = 'Sin teléfono asignado';
            }

            telefono.disabled = true;
            telefono.classList.add('bg-gray-300');
            telefono.classList.add('cursor-not-allowed');
            //edad
            let edadChecked = Array.from(edad).find(x => x.value == ui.item.age_range);
            edadChecked.checked = true;
            edad.forEach(e => {
                e.disabled = true;
                e.classList.remove('text-blue-600')
                e.classList.add('cursor-not-allowed', 'text-gray-500');
            })
            //sexo
            let sexoChecked = Array.from(sexo).find(x => x.value == ui.item.sex);
            sexoChecked.checked = true;
            sexo.forEach(e => {
                e.disabled = true;
                e.classList.remove('text-blue-600')
                e.classList.add('cursor-not-allowed', 'text-gray-500');
            })

            //provincia
            let provinceSelect = Array.from(provincia.options).find(opt => opt.value == ui.item.province);
            provinceSelect.selected = true;

            provincia.disabled = true;
            provincia.classList.add('bg-gray-300');
            provincia.classList.add('cursor-not-allowed');
            //distrito

            distrito.innerHTML = '';
            let items = distritos.filter(x => x.province_id == provincia.value);
            items.forEach(e => {
                if (e.district_id == ui.item.district) {
                    distrito.innerHTML += `<option value="${e.district_id}" selected>${e.name}</option>`;
                }
                else {
                    distrito.innerHTML += `<option value="${e.district_id}">${e.name}</option>`;
                }
            });

            //corregim
            corregimiento.innerHTML = '';
            items = corregimientos.filter(x => x.district_id == distrito.value);
            items.forEach(e => {
                if (e.township_id == ui.item.township) {
                    corregimiento.innerHTML += `<option value="${e.township_id}">${e.name}</option>`;
                }
                else {
                    corregimiento.innerHTML += `<option value="${e.township_id}">${e.name}</option>`;
                }
            });

            distrito.disabled = true;
            distrito.classList.add('bg-gray-300');
            distrito.classList.add('cursor-not-allowed');

            corregimiento.disabled = true;
            corregimiento.classList.add('bg-gray-300');
            corregimiento.classList.add('cursor-not-allowed');

            Toastify({
                text: "Visitante seleccionado",
                duration: 3000,
                style: {
                    background: '#10B981'
                }
            }).showToast();

            accion.innerHTML = '<i class="fas fa-eye"></i>';
            accion.classList.remove('bg-emerald-500', 'active:bg-emerald-600', 'hover:bg-emerald-700');
            accion.classList.add('bg-yellow-500', 'active:bg-yellow-600', 'hover:bg-yellow-700');
        }
    }

});

function triggerKeyup(element) {
    let changeEvent = new Event('keyup');
    element.dispatchEvent(changeEvent);
}

inputDocumento.addEventListener('keyup', evt => {

    if (evt.key != "Enter") {
        idHidden.value = '';
        nombreCliente.value = '';
        feedbackdocumento.textContent = '';
    }

});

agregar.addEventListener('click', evt => {
    let servicioAdd = servicios[categoria_servicio.value].find((value) => value.id == servicio.value);
    registrarServicio(categoria_servicio.value, servicioAdd.id, servicioAdd.name, servicioAdd.price, servicioAdd.measure);
});

let servicios_ag = [];

let contandorF = 0;
let html = '';

function registrarServicio(categoria_servicio, id_servicio, descripcion, precio = '0.00', measure = 'S/U') {

    contandorF++;

    let numeroItem = 'item' + contandorF;

    let validar = (categoria_servicio == 'areas') || (categoria_servicio == 'eventos');

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
              <button class="flex items-center justify-center p-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-full active:bg-blue-600 hover:bg-blue-700 btn-detalles" onclick="editarItem('${categoria_servicio}',${id_servicio},${numeroItem},'${measure}')">
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
                <input type="number" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 precio ${validar ? 'bg-gray-300 cursor-not-allowed' : ''}" value="${validar ? '0.00' : precio}" placeholder="Precio" min="0.00" step="0.01" onchange="cambiarValue(this,total_item${contandorF})" ${validar ? 'disabled' : ''} >
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
        categoria: categoria_servicio,
        servicio: id_servicio,
        numeroItem: numeroItem
    });


    calcular();

}

let respuesta;

function editarItem(categoria_servicio, id_servicio, numeroItem, unidad) {
    indice = servicios_ag.findIndex((value) => value.numeroItem == numeroItem.id);

    respuesta = servicios_ag[indice].hasOwnProperty('detalles');

    TIPO_TABLAS[categoria_servicio](categoria_servicio, id_servicio, numeroItem.id, unidad);

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
                modal_content.classList.remove('max-h-96', 'overflow-auto')

            }
            else {
                modal.classList.toggle('hidden');
            }

        });
    }

    function actualizar(evt) {

        const resultado = TIPO_ACTUALIZAR[categoria_servicio](numeroItem.id);

        if (!resultado) {
            modal.classList.toggle('hidden');

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

            const btnDetails = document.querySelector('#' + numeroItem.id + ' ' + 'button')
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

//Eventos
let categoriaEvento = [];
let selectorEvento;

let selectAreaEvento;
let inputHoraInicial;
let inputHoraFinal;
let inputFechaInicial;
let inputFechaFinal;
let inputPrecioEvento;

let respuestaEvento;

//Uso de maquina
let indice;
let areaConsumibles = [];
let selectorConsumible;
let selectorGanancia;

let inputPrecioUnitario;
let inputPrecioImpresion;

let inputCantidadUnidad;
let inputCantidadTiempo;

let inputCostoBase;
let inputCostoTotal;

let costoBase; //Calculo en linea

const TIPO_TABLAS = {
    "membresias": (categoria, id_servicio, numeroItem_id, unidad) => {
        modal_content.innerHTML = `
                    <main class="grid grid-cols-2 gap-5">
                    <label class="text-sm block">
                        <span class="text-gray-800 font-medium">Fecha inicial</span>
                        <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="date" name="fecha_inicial" value="${respuesta ? servicios_ag[indice].detalles.fecha_inicial : ''}" onchange="cambiarFechaFinal(this,${id_servicio})">
                        <span id="feedbackfechai" class="text-xs text-red-600 feed"></span>
                    </label>
                    <label class="text-sm block">
                        <span class="text-gray-800 font-medium">Fecha final</span>
                        <input class="mt-1 text-sm  w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300" type="date" name="fecha_final" value="${respuesta ? servicios_ag[indice].detalles.fecha_final : ''}">
                        <span id="feedbackfechaf" class="text-xs text-red-600 feed"></span>
                    </label>
                    </main>
               `;

        inputFechaFinal = document.querySelector('input[name="fecha_final"]');
    },
    "eventos": (categoria, id_servicio, numeroItem_id, unidad) => {

        modal_content.classList.add('max-h-96', 'overflow-auto');

        console.log(id_servicio);
        console.log(events);
        categoriaEvento = events.filter(x => x.category_id === id_servicio);

        modal_content.innerHTML = `
        <main class="grid grid-cols-1 gap-5 justify-items-center">
            <label class="text-sm w-full flex flex-col justify-center items-center gap-1">
                    <div class="w-full flex justify-center items-center gap-2">
                        <span class="text-gray-800 font-medium">Seleccione un evento</span>
                        <select class="text-sm w-1/2 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required="" name="evento_disponible" onchange="cambiarEvento(this)">
                        </select>
                        <button class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-transparent rounded-md focus:outline-none focus:shadow-outline-purple bg-emerald-500 active:bg-emerald-600 hover:bg-emerald-700" onclick="verTablaDetalles(this)"><i class="fas fa-eye"></i></button>
                    </div>
                    <span id="feedbackevento" class="w-full text-xs text-red-600 text-center feed"></span>
            </label>
                        
            <div class="w-full grid grid-cols-2 gap-5 hidden">
                <label class="text-sm block">
                    <span class="text-gray-800 font-medium">Área</span>
                    <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm bg-gray-300 cursor-not-allowed" required name="area" disabled></select>
                </label>

                <label class="text-sm block">
                    <span class="text-gray-800 font-medium">Precio</span>
                    <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm bg-gray-300 cursor-not-allowed" type="number" placeholder="Precio de evento" name="precio_evento" min="0.00" step="0.01" value="" disabled="">
                </label>
        
                <label class="text-sm block">
                    <span class="text-gray-800 font-medium">Fecha inicial</span>
                    <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm bg-gray-300 cursor-not-allowed" type="date" name="fecha_inicial" value="" disabled="">
                </label>

                <label class="text-sm block">
                    <span class="text-gray-800 font-medium">Fecha final</span>
                    <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm bg-gray-300 cursor-not-allowed" type="date" name="fecha_final" value="" disabled="">
                </label>

                <label class="text-sm block">
                    <span class="text-gray-800 font-medium">Hora inicial</span>
                    <input type="time" name="hora_inicial" class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm bg-gray-300 cursor-not-allowed" disabled>
                </label>

                <label class="text-sm block">
                    <span class="text-gray-800 font-medium">Hora final</span>
                    <input type="time" name="hora_final" class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm bg-gray-300 cursor-not-allowed" disabled>
                </label>
            </div>
        </main>`;

        selectorEvento = document.querySelector('select[name="evento_disponible"]');

        selectorEvento.innerHTML = '';

        if (categoriaEvento.length) {
            selectorEvento.innerHTML = '<option disabled selected value hidden> -- Eventos disponibles -- </option>';
            categoriaEvento.forEach((value) => {
                selectorEvento.innerHTML += `
<option value="${value.event_id}" ${respuesta ? servicios_ag[indice].detalles.event_id == value.event_id ? 'selected' : '' : ''} >${value.name}</option>`;
            });

            console.log(categoriaEvento);

            respuestaEvento = categoriaEvento.find(x => x.event_id == selectorEvento.value);

            inputPrecioEvento = document.querySelector('input[name="precio_evento"]'),
                selectAreaEvento = document.querySelector('select[name="area"]'),
                inputHoraInicial = document.querySelector('input[name="hora_inicial"]'),
                inputHoraFinal = document.querySelector('input[name="hora_final"]'),
                inputFechaInicial = document.querySelector('input[name="fecha_inicial"]'),
                inputFechaFinal = document.querySelector('input[name="fecha_final"]');
            console.log(respuestaEvento);
            if (respuestaEvento) {
                selectAreaEvento.innerHTML += `<option>${respuestaEvento.area_id}</option>`;
                inputHoraInicial.value = respuestaEvento.start_time;
                inputHoraFinal.value = respuestaEvento.end_time;
                inputFechaInicial.value = respuestaEvento.initial_date;
                inputFechaFinal.value = respuestaEvento.final_date;
                inputPrecioEvento.value = respuestaEvento.price;
            }

        }
        else {
            selectorEvento.innerHTML = `<option value selected hidden>No existen eventos</option>`;

        }
    },
    "areas": (categoria, id_servicio, numeroItem_id, unidad) => {

        modal_content.classList.add('max-h-96', 'overflow-auto');
        areaConsumibles = consumibles.filter(value => value.area_id == id_servicio);

        modal_content.innerHTML = `<main class="grid grid-cols-2 gap-5">
                    <label class="col-span-full text-sm block">
                        <span class="text-gray-800 font-medium">Tipo de insumo</span>
                        <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required="" name="tipo_consumible" onchange="cambiarPrecioUnitario()"></select>
                        <span id="feedbackconsumible" class="text-xs text-red-600 feed"></span>
                    </label>

                    <label class="text-sm block">
                        <span class="text-gray-800 font-medium">${unidad}</span>
                        <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" placeholder="Ingrese la cantidad de ${unidad.toLowerCase()}" name="cantidad_unidad" value="${respuesta ? servicios_ag[indice].detalles.cantidad_unidad : ''}" min="0" onchange="cambiarTotales(this)">
                        <span id="feedbackcantidadu" class="text-xs text-red-600 feed"></span>
                    </label>

                    <label class="text-sm block">
                        <span class="text-gray-800 font-medium">Precio unitario</span>
                        <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300" type="number" name="precio_unitario" placeholder="Ingrese el precio unitario" min="0.00" step="0.01" onchange="cambiarTotales(this)">
                        <span id="feedbackpreciou" class="text-xs text-red-600 feed"></span>
                    </label>

                    <label class="text-sm block">
                        <span class="text-gray-800 font-medium">Tiempo impresión (minutos)</span>
                        <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" placeholder="Ingrese la cantidad de minutos" name="cantidad_tiempo" value="${respuesta ? servicios_ag[indice].detalles.cantidad_tiempo : ''}" min="0" onchange="cambiarTotales(this)">
                        <span id="feedbackcantidadt" class="text-xs text-red-600 feed"></span>
                    </label>

                    <label class="text-sm block">
                        <span class="text-gray-800 font-medium">Precio impresión</span>
                        <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300" type="number" name="precio_impresion" 
                        placeholder="Ingrese el precio de impresión"
                        value="0.05" min="0.00" step="0.01" onchange="cambiarTotales(this)">
                        <span id="feedbackprecioi" class="text-xs text-red-600 feed"></span>
                    </label>

                    <label class="col-span-full text-sm block">
                        <span class="text-gray-800 font-medium">Costo base</span>
                        <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="number" name="costo_base" value="${respuesta ? servicios_ag[indice].detalles.costo_base : '0.00'}" disabled>
                    </label>
                    <label class="col-span-full text-sm block">
                        <span class="text-gray-800 font-medium">Porcentaje ganancia</span>
                        <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" name="tipo_ganancia" onchange="cambiarCostoTotal()">
                        <option value="0.15" >Estudiante - 15%</option>
                        <option value="0.2">Docente - 20%</option>
                        <option value="0.3" selected>Público - 30%</option>
                        </select>
                    </label>

                    <label class="col-span-full text-sm block">
                        <span class="text-gray-800 font-medium">Costo total</span>
                        <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="number" name="costo_total" value="${respuesta ? servicios_ag[indice].detalles.costo_total : '0.00'}" disabled>
                    </label>           
                    </main>`;

        selectorConsumible = document.querySelector('select[name="tipo_consumible"]'),
            selectorGanancia = document.querySelector('select[name="tipo_ganancia"]'),
            inputPrecioUnitario = document.querySelector('input[name="precio_unitario"]'),
            inputPrecioImpresion = document.querySelector('input[name="precio_impresion"]'),
            inputCantidadUnidad = document.querySelector('input[name="cantidad_unidad"]'),
            inputCantidadTiempo = document.querySelector('input[name="cantidad_tiempo"]'),
            inputCostoBase = document.querySelector('input[name="costo_base"]'),
            inputCostoTotal = document.querySelector('input[name="costo_total"]');

        selectorConsumible.innerHTML = '';

        if (areaConsumibles.length) {
            areaConsumibles.forEach((value) => {
                selectorConsumible.innerHTML = `<option value="${value.consumable_id}" ${respuesta ? servicios_ag[indice].detalles.tipo_consumible == value.consumable_id ? 'selected' : '' : ''} >${value.name}</option>`;
            })

            if (respuesta) {
                Array.from(selectorGanancia.options).forEach((opt) => {
                    if (opt.value == servicios_ag[indice].detalles.porcentaje_ganancia) {
                        opt.selected = true;
                    }
                });
            }

            cambiarPrecioUnitario();
        }
        else {
            selectorConsumible.innerHTML = `<option value selected hidden>No existen consumibles</option>`;
            inputPrecioUnitario.value = '0.00';
        }

    }
};

function cambiarEvento(e) {
    respuestaEvento = categoriaEvento.find(x => x.event_id == e.value);

    selectAreaEvento.innerHTML = `<option>${respuestaEvento.area_id}</option>`;
    inputHoraInicial.value = respuestaEvento.start_time;
    inputHoraFinal.value = respuestaEvento.end_time;
    inputFechaInicial.value = respuestaEvento.initial_date;
    inputFechaFinal.value = respuestaEvento.final_date;
    inputPrecioEvento.value = respuestaEvento.price;

    document.querySelector('#feedbackevento').textContent = '';

}

function verTablaDetalles(e) {

    if (selectorEvento.value) {
        if (e.children[0].classList.contains('fa-eye')) {
            e.innerHTML = '<i class="fas fa-eye-slash"></i>';
            e.classList.remove('bg-emerald-500', 'active:bg-emerald-600', 'hover:bg-emerald-700');
            e.classList.add('bg-red-500', 'active:bg-red-600', 'hover:bg-red-700');
            e.parentElement.parentElement.nextElementSibling.classList.remove('hidden');
        }
        else {
            e.innerHTML = '<i class="fas fa-eye"></i>';
            e.classList.remove('bg-red-500', 'active:bg-red-600', 'hover:bg-red-700');
            e.classList.add('bg-emerald-500', 'active:bg-emerald-600', 'hover:bg-emerald-700');

            e.parentElement.parentElement.nextElementSibling.classList.add('hidden');
        }
    }

}

function cambiarFechaFinal(e, id_servicio) {

    var DateTime = luxon.DateTime;

    const dt = DateTime.fromISO(e.value);

    if (id_servicio == 1) {
        inputFechaFinal.value = e.value;
    }
    else {
        let res;
        if (id_servicio == 2) { res = dt.plus({ days: 14 }); }
        else { res = dt.plus({ months: 1 }); }

        if (res.weekday == 6) {
            res = res.plus({ days: 2 });
            inputFechaFinal.value = res.toFormat('yyyy-MM-dd');
        }
        else if (res.weekday == 7) {
            res = res.plus({ days: 1 });
            inputFechaFinal.value = res.toFormat('yyyy-MM-dd');
        }
        else {
            inputFechaFinal.value = res.toFormat('yyyy-MM-dd');
        }
    }
}

function cambiarPrecioUnitario() {
    let consumibleSeleccionado = areaConsumibles.find(value => value.consumable_id == selectorConsumible.value);

    inputPrecioUnitario.value = consumibleSeleccionado.unit_price;

    cambiarTotales();
}

function cambiarTotales(e = null) {

    if (e) {
        e.nextElementSibling = '';
    }

    if (inputCantidadUnidad.value != 0 && inputCantidadTiempo.value != 0 && inputPrecioUnitario.value != '' && inputPrecioImpresion.value != '') {

        costoBase = (inputCantidadUnidad.value * inputPrecioUnitario.value) + (inputCantidadTiempo.value * inputPrecioImpresion.value);
        inputCostoBase.value = costoBase.toFixed(2);

        inputCostoTotal.value = (costoBase * (1 + parseFloat(selectorGanancia.value))).toFixed(2);

    }
}

function cambiarCostoTotal() {

    if (inputCostoTotal.value != 0) {

        inputCostoTotal.value = (costoBase * (1 + parseFloat(selectorGanancia.value))).toFixed(2);
    }
}

const TIPO_ACTUALIZAR = {
    "membresias": (numeroItem) => {
        let errores = {};

        if (document.querySelector('input[name="fecha_inicial"]').value.trim().length == 0) {
            errores.fecha_inicial = "Por favor, seleccione una fecha inicial";
            document.querySelector('#feedbackfechai').textContent = errores.fecha_inicial;
        }

        if (document.querySelector('input[name="fecha_final"]').value.trim().length == 0) {
            errores.fecha_final = "Por favor, seleccione una fecha final";
            document.querySelector('#feedbackfechaf').textContent = errores.fecha_final;
        }

        if (Object.keys(errores).length > 0) {
            const inputs = document.querySelectorAll('#modal-content input');
            inputs.forEach(x => {
                x.addEventListener('change', evt => {
                    if (evt.target.name == 'fecha_inicial') {
                        document.querySelectorAll('.feed').forEach(x => {
                            x.textContent = '';
                        });
                    }
                    else {
                        evt.target.nextElementSibling.textContent = '';
                    }
                })
            });

            return true;

        }
        else {
            servicios_ag[indice].detalles = {
                fecha_inicial: document.querySelector('input[name="fecha_inicial"]').value,
                fecha_final: document.querySelector('input[name="fecha_final"]').value
            }

            return false;
        }
    },
    "eventos": (numeroItem) => {
        let errores = {};

        if (selectorEvento.value.trim().length == 0) {
            if (selectorEvento.options.length == 1 && selectorEvento.options[0].hidden) {
                errores.evento = "En la categoría actual, no existen eventos disponibles.";
            }
            else {
                errores.evento = "Por favor, seleccione un evento";
            }
            document.querySelector('#feedbackevento').textContent = errores.evento;
        }

        if (Object.keys(errores).length > 0) {
            return true;
        }
        else {
            servicios_ag[indice].detalles = {
                event_id: selectorEvento.value
            }
            const precio = document.querySelector('#' + numeroItem + ' .precio');
            precio.value = inputPrecioEvento.value;

            triggerChange(precio);

            return false;
        }
    },
    "areas": (numeroItem) => {

        let errores = {};


        if (selectorConsumible.options.length == 1 && selectorConsumible.options[0].hidden) {
            errores.consumible = "En el área actual, no existen consumibles disponibles.";
            document.querySelector('#feedbackconsumible').textContent = errores.consumible;
        }

        if (inputCantidadUnidad.value.trim().length == 0 || inputCantidadUnidad.value == 0) {
            errores.cantidadunidad = "Por favor, ingrese la cantidad de unidad.";
            document.querySelector('#feedbackcantidadu').textContent = errores.cantidadunidad;
        }

        if (inputCantidadTiempo.value.trim().length == 0 || inputCantidadUnidad.value == 0) {
            errores.cantidadtiempo = "Por favor, ingrese la cantidad de tiempo.";
            document.querySelector('#feedbackcantidadt').textContent = errores.cantidadtiempo;
        }

        if (inputPrecioUnitario.value.trim().length == 0) {
            errores.preciounitario = "Por favor, ingrese el precio unitario.";
            document.querySelector('#feedbackpreciou').textContent = errores.preciounitario;
        }

        if (inputPrecioImpresion.value.trim().length == 0) {
            errores.precioimpresion = "Por favor, ingrese el precio de impresión.";
            document.querySelector('#feedbackprecioi').textContent = errores.precioimpresion;
        }

        if (Object.keys(errores).length > 0) {
            const inputs = document.querySelectorAll('#modal-content input');
            inputs.forEach(x => {
                x.addEventListener('change', evt => {
                    if (evt.target.name == 'fecha_inicial') {
                        document.querySelectorAll('.feed').forEach(x => {
                            x.textContent = '';
                        });
                    }
                    else {
                        evt.target.nextElementSibling.textContent = '';
                    }
                })
            });

            return true;
        }
        else {
            servicios_ag[indice].detalles = {
                tipo_consumible: selectorConsumible.value,
                cantidad_unidad: inputCantidadUnidad.value,
                precio_unitario: inputPrecioUnitario.value,
                cantidad_tiempo: inputCantidadTiempo.value,
                precio_impresion: inputPrecioImpresion.value,
                costo_base: inputCostoBase.value,
                porcentaje_ganancia: selectorGanancia.value,
                costo_total: inputCostoTotal.value
            }

            const precio = document.querySelector('#' + numeroItem + ' .precio');
            precio.value = inputCostoTotal.value;

            triggerChange(precio);

            return false;
        }
    }
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
                    <p class="font-medium">Añadir servicios a la compra</p>
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

let total;

function calcular() {
    // obtenemos todas las filas del tfoot
    let filas = document.querySelectorAll("#detalle_venta tr");

    total = 0;

    // recorremos cada una de las filas
    filas.forEach((e) => {

        // obtenemos las columnas de cada fila
        let columnas = e.querySelectorAll("td");

        // obtenemos los valores de la cantidad y importe
        let importe = parseFloat(columnas[3].textContent);

        total += importe;
    });

    filas = document.querySelectorAll("#detalle_totales #total td");
    filas[2].textContent = total.toFixed(2);

}

let btn_anular = document.querySelector('#anular');

btn_anular.addEventListener('click', e => {
    location.reload();
});