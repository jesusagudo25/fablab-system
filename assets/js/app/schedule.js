let reasonHTML = '';
posting = $.post('./functions.php', { solicitud: 'raz' });

posting.done(function (data) {
    data.forEach(x => {
        reasonHTML += `
            <option value="${x.reason_id}" class="${x.time == 1 ? 'notfree' : 'free'}">${x.name}</option>;
        `;
    });
});

var calendarEl = document.getElementById('calendar');
var calendar = new FullCalendar.Calendar(calendarEl, { //Timezone local en pc de secretaria
    locale: 'es-us',
    weekends: false,
    dayMaxEvents: true,
    displayEventEnd: true,
    selectable: true,
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
    },
    events: './functions.php',
    eventTimeFormat: {
        hour: 'numeric',
        minute: '2-digit'
    },
    eventColor: '#2563eb',
    dateClick: function (info) {
        titulo_modal.textContent = 'Nueva reservación';
        guardar.textContent = 'Guardar';

        eliminar.classList.add('hidden');
        TIPO_TABLAS['booking']();

        tipoDocumento = document.querySelector('select[name="tipodocumento"]');
        tituloDocumento = document.querySelector('#tituloDocumento');
        inputDocumento = document.querySelector('input[name="documento"]');
        inputName = document.querySelector('input[name="name"]');
        buttonEditAreas = document.querySelector('button[name="editareas"]');
        inputDate = document.querySelector('#modal-content input[name="fecha"]');
        reasonVisit = document.querySelector('select[name="razonvisita"]');
        inputObservation = document.querySelector('textarea[name="observation"]');
        containerArea = document.querySelector('#containerarea');
        containerTiempo = document.querySelector('#containertiempo');

        inputDate.value = info.dateStr;

        tipoDocumento.addEventListener('change', evt => {
            TIPOS_DOCUMENTOS[evt.target.value]();
            inputDocumento.value = '';
            feedbackdocumento.textContent = '';
        });

        buttonEditAreas.addEventListener('click', seleccionarAreas);

        footer_modal.classList.remove('hidden');
        modal.classList.toggle('hidden');

        guardar.addEventListener('click', crear);

        closeModal.forEach(e => {
            e.addEventListener('click', cerrarCrear);
        });
    },
    eventClick: function (info) {
        var eventObj = info.event;
        if (eventObj.classNames[0] == 'events') {
            TIPO_TABLAS[eventObj.classNames[0]]();
            $.ajax({
                url: "./functions.php",
                type: "POST",
                datatype: "json",
                data: {
                    solicitud: "evt",
                    id: eventObj.groupId,
                },
                success: function (data) {
                    document.querySelector('input[name="nombre_evento"]').value = data['name'];
                    document.querySelector('input[name="precio_evento"]').value = data['price'];
                    document.querySelector('select[name="area"]').innerHTML += `<option>${data['area_name']}</option>`;
                    document.querySelector('select[name="categoria"]').innerHTML += `<option>${data['category_name']}</option>`;
                    document.querySelector('input[name="fecha_inicial"]').value = data['initial_date'];
                    document.querySelector('input[name="fecha_final"]').value = data['final_date'];
                    document.querySelector('input[name="hora_inicial"]').value = data['start_time'];
                    document.querySelector('input[name="hora_final"]').value = data['end_time'];

                    footer_modal.classList.add('hidden');
                    modal.classList.toggle('hidden');

                    closeModal.forEach(e => {
                        e.addEventListener('click', cerrarCrear);
                    });
                }
            });
        }
        else {
            eliminar.classList.remove('hidden');

            const booking_id = eventObj.groupId ? eventObj.groupId : eventObj.id;
            titulo_modal.textContent = 'Editar reservación';
            guardar.textContent = 'Actualizar';

            TIPO_TABLAS[eventObj.classNames[0]]();

            tipoDocumento = document.querySelector('select[name="tipodocumento"]');
            tituloDocumento = document.querySelector('#tituloDocumento');
            inputDocumento = document.querySelector('input[name="documento"]');
            inputName = document.querySelector('input[name="name"]');
            buttonEditAreas = document.querySelector('button[name="editareas"]');
            inputDate = document.querySelector('#modal-content input[name="fecha"]');
            reasonVisit = document.querySelector('select[name="razonvisita"]');
            inputObservation = document.querySelector('textarea[name="observation"]');
            containerArea = document.querySelector('#containerarea');
            containerTiempo = document.querySelector('#containertiempo');

            tipoDocumento.addEventListener('change', evt => {
                TIPOS_DOCUMENTOS[evt.target.value]();
                inputDocumento.value = '';
                feedbackdocumento.textContent = '';
            });

            $.ajax({
                url: "./functions.php",
                type: "POST",
                datatype: "json",
                data: {
                    solicitud: "b_id",
                    id: booking_id,
                },
                success: function (data) {
                    areasActualizar = data['areas'];
                    tipoDocumento.value = data['booking']['document_type'];
                    triggerChange(tipoDocumento);

                    inputDocumento.value = data['booking']['document'];
                    inputName.value = data['booking']['name'];
                    reasonVisit.value = data['booking']['reason_id'];

                    optionSelected = reasonVisit.options[reasonVisit.selectedIndex];

                    triggerChange(reasonVisit);

                    inputDate.value = data['booking']['date'];

                    inputObservation.value = data['booking']['observation'];

                    buttonEditAreas.value = booking_id;
                    buttonEditAreas.addEventListener('click', seleccionarAreas);

                    footer_modal.classList.remove('hidden');
                    modal.classList.toggle('hidden');

                    guardar.value = booking_id;
                    guardar.addEventListener('click', crear);

                    eliminar.value = booking_id;
                    eliminar.addEventListener('click', eliminarReserva);

                    closeModal.forEach(e => {
                        e.addEventListener('click', cerrarCrear);
                    });
                }
            });
        }

    },
    eventDrop: function (eventDropInfo) {
        const id = eventDropInfo.event.id == '' ? eventDropInfo.event.groupId : eventDropInfo.event.id;

        $.ajax({
            url: "./functions.php",
            type: "POST",
            datatype: "json",
            data: {
                solicitud: 'b_drop',
                id: id,
                date: eventDropInfo.event.startStr.split('T')[0]
            }
        });

    }
});

calendar.render();

//-----------

let buttonEditAreas,
    tipoDocumento,
    tituloDocumento,
    inputDocumento,
    inputName,
    inputDate,
    reasonVisit,
    containerArea,
    inputObservation,
    optionSelected;

const TIPO_TABLAS = {
    "booking": () => {
        modalContent.innerHTML = `
            <label class="text-sm block">
                <span class="text-gray-800 font-medium">Seleccione el tipo de documento</span>
                <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="tipodocumento">
                    <option value="R">RUC</option>
                    <option value="C">Cédula</option>
                    <option value="P">Pasaporte</option>
                </select>
            </label>

            <label class="text-sm block mt-5">
                <span class="text-gray-800 font-medium" id="tituloDocumento">Numero de RUC</span>
                <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese el número de RUC con guiones" name="documento" required type="text" autocomplete="false">
                <span id="feedbackdocumento" class="text-xs text-red-600 "></span>
            </label>

            <label class="text-sm block mt-5">
                <span class="text-gray-800 font-medium">Nombre</span>
                <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese el nombre del cliente" type="text" name="name" required autocomplete="off">
                <span id="feedbacknombre" class="text-xs text-red-600 feed"></span>
            </label>
            
            <label class="block text-sm mt-5">
                <span class="text-gray-800 font-medium">Seleccione la razón de visita</span>
                <select required="" name="razonvisita" class="mt-1 text-sm block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        ${reasonHTML}
                </select>
            </label>

            <div class="text-sm mt-5" id="containerarea">
                <span class="text-gray-800 font-medium">Áreas de trabajo a visitar</span>
                <button class="mt-1 align-bottom flex items-center justify-center cursor-pointer leading-5 transition-colors duration-150 font-medium focus:outline-none px-3 py-1 rounded-md text-sm text-white bg-blue-500 border border-transparent active:bg-blue-600 hover:bg-blue-700" name="editareas">Agregar</button>
                <span id="feedbackbuttonareas" class="text-xs text-red-600 feed"></span>
            </div>

            <label class="block text-sm mt-5">
                <span class="text-gray-800 font-medium">Fecha de reservación</span>
                <input class="text-sm mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="date" name="fecha" disabled>
                <span id="feedbackfecha" class="text-xs text-red-600 feed"></span>
            </label>

            <label class="block text-sm mt-5">
                <span class="text-gray-800 font-medium">Observación complementaria</span>
                <textarea class="text-sm mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese una observación complementaria" name="observation"></textarea>
            </label>
               `;
    },
    "events": () => {
        titulo_modal.textContent = 'Evento';
        modalContent.innerHTML = `
            <div class="w-full grid grid-cols-2 gap-5">
                <label class="text-sm block">
                    <span class="text-gray-800 font-medium">Nombre</span>
                    <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm bg-gray-300 cursor-not-allowed" type="text" placeholder="Ingrese el nombre del evento" name="nombre_evento" value="" disabled>
                </label>
                    
                <label class="text-sm block">
                    <span class="text-gray-800 font-medium">Precio</span>
                    <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm bg-gray-300 cursor-not-allowed" type="number" placeholder="Precio de evento" name="precio_evento" min="0.00" step="0.01" value="" disabled="">
                </label>

                <label class="text-sm block">
                    <span class="text-gray-800 font-medium">Área</span>
                    <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm bg-gray-300 cursor-not-allowed" required name="area" disabled></select>
                </label>

                <label class="text-sm block">
                    <span class="text-gray-800 font-medium">Categoría</span>
                    <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm bg-gray-300 cursor-not-allowed" required name="categoria" disabled></select>
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
        `;
    }
};

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

const modalContent = document.querySelector('#modal-content'),
    areasTrabajo = document.querySelectorAll('input[type="checkbox"]'),
    closeModal = document.querySelectorAll('.close'),
    closeModal_areas = document.querySelectorAll('.close-areas'),
    modal = document.querySelector('#modal'),
    footer_modal = document.querySelector('#modal footer'),
    modal_areas = document.querySelector('#modal-areas'),
    titulo_modal = document.querySelector('#titulo-modal'),
    guardar = document.querySelector('button[name="guardar"]'),
    eliminar = document.querySelector('button[name="eliminar"]'),
    guardar_areas = document.querySelector('button[name="guardar-areas"]'),
    feedsareas = document.querySelectorAll('#modal-areas-content .feed'),
    feeds = document.querySelectorAll('#modal-content .feed');

//Modal principal
//Para actualizar el modal crear
function crear(e) {
    let errores = {};

    let datos = {
        document_type: tipoDocumento.value,
        reason_id: reasonVisit.value,
        date: inputDate.value,
        observation: inputObservation.value
    };

    if (inputDocumento.value.trim().length == 0) {
        if (tipoDocumento.value == 'R') {
            errores.documento = "Por favor, proporcione un RUC";
        }
        else if (tipoDocumento.value == 'C') {
            errores.documento = "Por favor, proporcione una cédula";
        }
        else {
            errores.documento = "Por favor, proporcione un pasaporte";
        }
        feedbackdocumento.textContent = errores.documento;
        inputDocumento.addEventListener('change', cambioValor);
    }
    else {
        datos["document"] = inputDocumento.value;
    }

    if (inputName.value.trim().length == 0) {
        errores.nombre = "Por favor, proporcione un nombre";
        feedbacknombre.textContent = errores.nombre;
        inputName.addEventListener('change', cambioValor);
    }
    else {
        datos["name"] = inputName.value;
    }

    if (optionSelected.classList.contains('notfree')) {
        if (areasActualizar.length == 0) {
            buttonEditAreas.classList.remove('bg-blue-500', 'active:bg-blue-600', 'hover:bg-blue-700');
            buttonEditAreas.classList.add('bg-red-500', 'active:bg-red-600', 'hover:bg-red-700');
            errores.areas = "Por favor, seleccione las áreas a visitar";
            feedbackbuttonareas.textContent = errores.areas;
        }
        else {
            datos["areasChecked"] = areasActualizar;
        }
    }

    if (Object.keys(errores).length == 0) {

        if (e.target.textContent == 'Guardar') {
            $.ajax({
                url: "./functions.php",
                type: "POST",
                datatype: "json",
                data: {
                    datos: datos,
                    solicitud: 'b'
                },
                success: function (data) {
                    areasActualizar = [];
                    modal.classList.toggle('hidden');
                    calendar.refetchEvents();
                    guardar.removeEventListener('click', crear);
                    closeModal.forEach(evt => {
                        evt.removeEventListener('click', cerrarCrear);
                    });
                    Toastify({
                        text: "Reserva creada!",
                        duration: 3000,
                        style: {
                            background: '#10B981'
                        }
                    }).showToast();
                }
            });
        }
        else {
            $.ajax({
                url: "./functions.php",
                type: "POST",
                datatype: "json",
                data: {
                    datos: datos,
                    solicitud: 'b_up',
                    id: e.target.value
                },
                success: function (data) {
                    areasActualizar = [];
                    modal.classList.toggle('hidden');
                    calendar.refetchEvents();
                    guardar.removeEventListener('click', crear);
                    closeModal.forEach(evt => {
                        evt.removeEventListener('click', cerrarCrear);
                    });
                    Toastify({
                        text: "Reserva actualizada!",
                        duration: 3000,
                        style: {
                            background: '#10B981'
                        }
                    }).showToast();
                }
            });
        }
    }
}

//Para cerrar el modal crear
function cerrarCrear(e) {
    modal.classList.toggle('hidden');
    guardar.removeEventListener('click', crear);
    closeModal.forEach(e => {
        e.removeEventListener('click', cerrarCrear);
    });
    areasActualizar = [];
}

//Para eliminar una reserva
function eliminarReserva(e) {
    modal.classList.toggle('hidden');
    Swal.fire({
        title: '¿Estás seguro?',
        text: "La reservación será eliminada y no podrá ser recuperada.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#10B981',
        cancelButtonColor: '#EF4444',
        confirmButtonText: 'Si, borrar ahora!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "./functions.php",
                type: "POST",
                datatype: "json",
                data: {
                    solicitud: "d",
                    id: e.target.value
                },
                success: function (data) {
                    areasActualizar = [];
                    calendar.refetchEvents();
                    guardar.removeEventListener('click', crear);
                    closeModal.forEach(evt => {
                        evt.removeEventListener('click', cerrarCrear);
                    });
                    Toastify({
                        text: "Reserva eliminada!",
                        duration: 3000,
                        style: {
                            background: '#10B981'
                        }
                    }).showToast();
                }
            });
        }
        else {
            modal.classList.toggle('hidden');
        }
    })
}

//Funcion para detectar cambio de valor en un campo incorrecto<-validate
function cambioValor(e) {
    e.target.nextElementSibling.textContent = '';
    e.target.removeEventListener('change', cambioValor);
}

let areasActualizar = [];

//Agregando eventos click a areas de trabajo
areasTrabajo.forEach(evt => {
    evt.addEventListener('click', x => {
        const areaCheck = document.querySelector('#area' + x.target.value);
        if (areaCheck.nextElementSibling.classList.contains('feed')) {
            areaCheck.parentElement.classList.toggle('p-3');
            areaCheck.parentElement.classList.toggle('px-3', 'pt-3');
        }
        else {
            areaCheck.nextElementSibling.classList.toggle('mt-4');
        }
        areaCheck.classList.toggle('hidden');
        document.querySelector('input[name="arrival_time_area' + x.target.value + '"]').value = '';
        document.querySelector('input[name="departure_time_area' + x.target.value + '"]').value = '';
        feedbackareas.textContent = '';
    });
});

//Modal-areas para cuando se va a crear/editar una reservacion
function seleccionarAreas(e) {
    feedsareas.forEach(x => {
        x.textContent = '';
    });

    areasTrabajo.forEach(x => {
        x.checked = false;
        const areaCheck = document.querySelector('#area' + x.value);
        if (areaCheck.nextElementSibling.classList.contains('feed')) {
            areaCheck.parentElement.classList.add('p-3');
            areaCheck.parentElement.classList.remove('px-3', 'pt-3');
        }
        else {
            areaCheck.nextElementSibling.classList.add('mt-4');
        }
        document.querySelector('#area' + x.value).classList.add('hidden');
        document.querySelector('input[name="arrival_time_area' + x.value + '"]').value = '';
        document.querySelector('input[name="departure_time_area' + x.value + '"]').value = '';

    });

    if (areasActualizar.length) {
        areasActualizar.forEach(x => {
            document.querySelector('input[name="' + 'areacheck' + x.area_id + '"]').checked = true;
            const areaCheck = document.querySelector('#area' + x.area_id);
            if (areaCheck.nextElementSibling.classList.contains('feed')) {
                areaCheck.parentElement.classList.remove('p-3');
                areaCheck.parentElement.classList.add('px-3', 'pt-3');
            }
            else {
                areaCheck.nextElementSibling.classList.remove('mt-4');
            }
            document.querySelector('#area' + x.area_id).classList.remove('hidden');
            document.querySelector('input[name="arrival_time_area' + x.area_id + '"]').value = x.arrival_time;
            document.querySelector('input[name="departure_time_area' + x.area_id + '"]').value = x.departure_time;
        });
    }

    modal_areas.classList.toggle('hidden');

    closeModal_areas.forEach(e => {
        e.addEventListener('click', cerrarEditarAreas);
    });

    guardar_areas.addEventListener('click', actualizarAreas);
}

//Metodo cerrar modal-areas
function cerrarEditarAreas(e) {
    modal_areas.classList.toggle('hidden');
    guardar_areas.removeEventListener('click', actualizarAreas);
    closeModal_areas.forEach(evt => {
        evt.removeEventListener('click', cerrarEditarAreas);
    });
}

//Metodo actualizar modal-areas
function actualizarAreas(evt) {
    let errores = {};

    let areasTempo = [];

    areasTrabajo.forEach(x => {

        if (x.checked) {
            let area = {
                area_id: x.value,
                arrival_time: document.querySelector('input[name="' + 'arrival_time_area' + x.value + '"]').value,
                departure_time: document.querySelector('input[name="' + 'departure_time_area' + x.value + '"]').value
            }

            if (area.arrival_time.trim().length == 0 && area.departure_time.trim().length == 0) {
                errores['area' + x.value] = "Por favor, proporcione una hora de llegada y salida";
                document.querySelector('#feedbackarea' + x.value).textContent = errores['area' + x.value];

                document.querySelector('input[name="arrival_time_area' + x.value + '"]').addEventListener('change', validateTime);

                document.querySelector('input[name="departure_time_area' + x.value + '"]').addEventListener('change', validateTime);
            }
            else if (area.arrival_time.trim().length == 0) {
                errores['area' + x.value] = "Por favor, proporcione una hora de llegada";

                document.querySelector('#feedbackarea' + x.value).textContent = errores['area' + x.value];

                document.querySelector('input[name="arrival_time_area' + x.value + '"]').addEventListener('change', validateTime);
            }
            else if (area.departure_time.trim().length == 0) {
                errores['area' + x.value] = "Por favor, proporcione una hora de salida";

                document.querySelector('#feedbackarea' + x.value).textContent = errores['area' + x.value];

                document.querySelector('input[name="departure_time_area' + x.value + '"]').addEventListener('change', validateTime);
            }

            function validateTime(e) {
                if (e.target.parentElement.htmlFor == 'arrival_time') {
                    if (e.target.value.trim().length != 0 && e.target.parentElement.nextElementSibling.children[0].value.trim().length != 0) {
                        document.querySelector('#feedbackarea' + x.value).textContent = '';
                        e.target.removeEventListener('change', validateTime);
                    }
                    else {
                        document.querySelector('#feedbackarea' + x.value).textContent = 'Por favor, proporcione una hora de salida';
                    }
                }
                else {
                    if (e.target.value.trim().length != 0 && e.target.parentElement.previousElementSibling.children[0].value.trim().length != 0) {
                        document.querySelector('#feedbackarea' + x.value).textContent = '';
                        e.target.removeEventListener('change', validateTime);
                    }
                    else {
                        document.querySelector('#feedbackarea' + x.value).textContent = 'Por favor, proporcione una hora de llegada';
                    }
                }
            }
            areasTempo.push(area);
        }
    });

    if (areasTempo.length == 0) {
        errores.areas = "Por favor, seleccione las áreas deseadas";
        feedbackareas.textContent = errores.areas;
    }

    if (Object.keys(errores).length == 0) {

        areasActualizar = Array.from(areasTempo);
        modal_areas.classList.toggle('hidden');
        guardar_areas.removeEventListener('click', actualizarAreas);
        closeModal_areas.forEach(e => {
            e.removeEventListener('click', cerrarEditarAreas);
        });
        buttonEditAreas.classList.remove('bg-red-500', 'active:bg-red-600', 'hover:bg-red-700');
        buttonEditAreas.classList.add('bg-blue-500', 'active:bg-blue-600', 'hover:bg-blue-700');
        feedbackbuttonareas.textContent = '';
        Toastify({
            text: "Areas Actualizadas!",
            duration: 3000,
            style: {
                background: '#10B981'
            }
        }).showToast();
    }

}

//Trigger
function triggerChange(element) {
    let changeEvent = new Event('change');
    element.dispatchEvent(changeEvent);
}