let reasonVisits = [];
let optionSelected,
    buttonEditAreas;

posting = $.post('./functions.php', { solicitud: 'raz' });

posting.done(function (data) {
    reasonVisits = data;
});

tablaVisitas = $('#datatable-json').DataTable({
    language: { url: "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json" },
    "processing": true,
    "serverSide": true,
    "ajax": "./functions.php",
    "columnDefs": [
        {
            "data": null,
            render: function (data, type, row) {
                return data[3];
            },
            "targets": 4
        },
        {
            "data": null,
            render: function (data, type, row) {
                if (data[4]) {
                    return '<button value="' + data[0] + '" type="button" name="desactivar" class="flex items-center justify-between text-2xl px-1 font-medium leading-5 text-emerald-500 rounded-lg focus:outline-none focus:shadow-outline-gray .btn-borrar" onclick="interruptor(this)"><i class="fas fa-toggle-on"></i></button>';
                }
                else {
                    return '<button value="' + data[0] + '" type="button" name="activar" class="flex items-center justify-between text-2xl font-medium px-1 leading-5 text-red-500 rounded-lg focus:outline-none focus:shadow-outline-gray .btn-borrar" onclick="interruptor(this)"><i class="fas fa-toggle-off"></i></button>';
                }
            },
            "targets": 5
        },
        {
            "data": null,
            render: function (data, type, row) {
                return '<div class="flex gap-2"><button value="' + data[0] + '" type="button" class="flex items-center justify-between px-2 py-2 text-lg font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray btn-editar" onclick="editarVisita(this)"><i class="fas fa-users"></i></button> <div class="flex gap-2"><button value="' + data[0] + '" type="button" class="flex items-center justify-between px-2 py-2 text-lg font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray btn-editar" onclick="editarVisita(this)"><i class="fas fa-clipboard-check"></i></button> <button value="' + data[0] + '" type="button" class="flex items-center justify-between px-2 py-2 text-lg font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray btn-editar" onclick="editarVisita(this)"><i class="fas fa-edit"></i></button></div>';
            },
            "targets": 6
        },
        { 'visible': false, 'targets': [0] }
    ],
    "order": [[0, "desc"]]
});

const modal_content = document.querySelector('#modal-content'),
    modal_content_areas = document.querySelector('#modal-areas-content'),
    closeModal = document.querySelectorAll('.close'),
    guardar = document.querySelector('button[name="guardar"]'),
    modal = document.querySelector('#modal'),
    modalAreas = document.querySelector('#modal-areas'),
    areasContent = document.querySelector('#areas-content'),
    customersContent = document.querySelector('#customers-content'),
    closeAreas = document.querySelectorAll('.cancelar-areas'),
    guardarAreas = document.querySelector('button[name="guardar-areas"]'),
    areasTrabajo = document.querySelectorAll('input[type="checkbox"]'),
    titulo_modal = document.querySelector('#titulo-modal'),
    titulo_modal_areas = document.querySelector('#titulo-modal-areas'),
    footer_modal = document.querySelector('#footer-modal'),
    feedsareas = document.querySelectorAll('#modal-areas-content .feed');

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

//Consultar solamente las areas en el modal principal
function verAreas(e) {

    modal_content.innerHTML = `
                    <div class="text-sm" id="containerarea">
                            <span class="text-gray-800 inline-block mb-4 font-medium">Áreas de trabajo visitadas</span></div>`;

    $.ajax({
        url: "./functions.php",
        type: "POST",
        datatype: "json",
        data: {
            solicitud: "id_va",
            id: e.value,
        },
        success: function (data) {
            data.forEach(v => {
                document.querySelector('#containerarea').innerHTML += `
       <label class="flex items-center">
                                    <input type="checkbox" value="${v.area_id}" name="areacheck${v.area_id}" class="rounded border-gray-300 text-gray-500 shadow-sm cursor-not-allowed" checked disabled>
                                    <span class="ml-2">${v.name}</span>
                            </label>

                            <div class="p-3" id="area${v.area_id}">
                                    <label for="arrival_time" class="mr-6">Hora de llegada:
                                        <input type="time" name="arrival_time_area${v.area_id}" class="text-sm p-1.5 m-1 rounded-md border-gray-300 shadow-sm bg-gray-300 cursor-not-allowed" value="${v.arrival_time ? v.arrival_time : ''}" min="08:00" max="16:00" disabled>
                                    </label>

                                    <label for="departure_time">Hora de salida:
                                        <input type="time" name="departure_time_area${v.area_id}" class="text-sm p-1.5 m-1 rounded-md border-gray-300 shadow-sm bg-gray-300 cursor-not-allowed" value="${v.departure_time ? v.departure_time : ''}" min="08:00" max="16:00" disabled>
                                    </label>
                                </div>
              `;
            });

            footer_modal.classList.add('hidden');
            modal.classList.toggle('hidden');

            closeModal.forEach(e => {
                e.addEventListener('click', cerrarVerAreas);
            });

            function cerrarVerAreas(e) {
                modal.classList.toggle('hidden');
                closeModal.forEach(e => {
                    e.removeEventListener('click', cerrarVerAreas);
                });
            }

        }
    });
}

//Editar visita -> Modal principal
function editarVisita(e) {

    titulo_modal.textContent = 'Editar visita';
    $.ajax({
        url: "./functions.php",
        type: "POST",
        datatype: "json",
        data: {
            solicitud: "id_v",
            id: e.value,
        },
        success: function (data) {
            areasActualizar = data['areas'];
            visitantesActualizar = data['customers'];
            modal_content.innerHTML = `
        <label class="block text-sm">
            <span class="text-gray-800 font-medium">Seleccione la razón de visita</span>
            <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required name="razonvisita">
            </select>
        </label>

        <div class="text-sm mt-5" id="container-clientes">
            <span class="text-gray-800 font-medium">Visitante</span>
            <button class="mt-1 align-bottom flex items-center justify-center cursor-pointer leading-5 transition-colors duration-150 font-medium  focus:outline-none px-3 py-1 rounded-md text-sm text-white bg-blue-500 border border-transparent active:bg-blue-600 hover:bg-blue-700" name="editarvisitantes" value="${e.value}" onclick="editarVisitantes(this)">Editar</button>
            <span id="feedbackbuttonareas" class="text-xs text-red-600 feed"></span>
        </div> 

        <label class="block text-sm mt-5">
            <span class="text-gray-800 font-medium">Seleccione la fecha de la visita</span>
            <input class="text-sm block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="date" name="fecha">
            <span id="feedbackfecha" class="text-xs text-red-600 feed"></span>
        </label>
            
        <div class="text-sm mt-5">
            <span class="text-gray-800 font-medium">Áreas de trabajo visitadas</span>
            <button class="mt-1 align-bottom flex items-center justify-center cursor-pointer leading-5 transition-colors duration-150 font-medium focus:outline-none px-3 py-1 rounded-md text-sm  text-white bg-blue-500 border border-transparent active:bg-blue-600 hover:bg-blue-700" name="editareas" value="${e.value}" onclick="editarAreas(this)">Editar</button>
            <span id="feedbackbuttonareas" class="text-xs text-red-600 feed"></span>
        </div>

        <label class="block text-sm mt-5">
            <span class="text-gray-800 font-medium">Observación complementaria</span>
            <textarea class="text-sm mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese una observación complementaria" name="observacion"></textarea>
        </label>`;

            const fechaVisita = document.querySelector('input[name="fecha"]'),
                razonVisita = document.querySelector('select[name="razonvisita"]'),
                observacion = document.querySelector('textarea[name="observacion"]'),
                buttonEditAreas = document.querySelector('button[name="editareas"]');

            reasonVisits.forEach(value => {
                razonVisita.innerHTML += `
                        <option class="${value.time == 1 ? 'notfree' : 'free'}" value="${value.reason_id}" ${value.reason_id == data['visits']['reason_id'] ? 'selected' : ''} >${value.name}</option>`;
            });

            fechaVisita.value = data['visits']['date'];
            observacion.value = data['visits']['observation'];

            footer_modal.classList.remove('hidden');
            modal.classList.toggle('hidden');

            guardar.addEventListener('click', actualizar);
            closeModal.forEach(e => {
                e.addEventListener('click', cerrarActualizar);
            });

            function cerrarActualizar(e) {
                modal.classList.toggle('hidden');
                guardar.removeEventListener('click', actualizar);
                closeModal.forEach(evt => {
                    evt.removeEventListener('click', cerrarActualizar);
                });
                areasActualizar = [];
                titulo_modal.textContent = 'áreas visitadas';
            }

            function actualizar(evt) {

                //No se valida las areasActualizar vacias cuando se cambia de razonesfree->notfree
                let errores = {};

                //Refactorizar----------------------------
                let datos = {
                    reason_id: razonVisita.value,
                    observation: observacion.value
                };



                if (fechaVisita.value.trim().length == 0) {
                    errores.fecha = "Por favor, seleccione una fecha";
                    feedbackfecha.textContent = errores.fecha;
                }
                else {
                    datos["date"] = fechaVisita.value;
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
                    $.ajax({
                        url: "./functions.php",
                        type: "POST",
                        datatype: "json",
                        data: {
                            solicitud: "up_v",
                            datos: datos,
                            visit_id: e.value
                        },
                        success: function (data) {
                            titulo_modal.textContent = 'áreas visitadas';
                            modal.classList.toggle('hidden');
                            tablaVisitas.ajax.reload();
                            guardar.removeEventListener('click', actualizar);
                            closeModal.forEach(e => {
                                e.removeEventListener('click', cerrarActualizar);
                            });
                            areasActualizar = [];
                            Toastify({
                                text: "Visita Actualizada",
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
    });
}

//Metodo de evento para editar las visitas
let visitantesActualizar = [];

function editarVisitantes(e) {

    const tipoDocumento = document.querySelector('select[name="tipodocumento"]'),
        razonVisita = document.querySelector('select[name="razonvisita"]'),
        inputDocumento = document.querySelector('input[name="documento"]'),
        listaVisitantes = document.querySelector('#lista-visitantes');

    feedsareas.forEach(x => {
        x.textContent = '';
    });

    //Cargar información inicial
    if (visitantesActualizar.length) {
        visitantesActualizar.forEach(x => {
            html =
            `
        <tr class="bg-white border-b">
            <td class="p-2">
                <div class="flex items-center text-sm">
                    <p class="font-medium">${x.name}</p>
                </div>
            </td>
            <td class="p-2">
                <button class="flex items-center justify-between px-2 py-2 text-medium font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray" value='${x.customer_id}'>
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        </tr>
        `;

        listaVisitantes.innerHTML += html;
        });

    }
    modalAreas.classList.toggle('hidden');
    areasContent.classList.add('hidden');
    customersContent.classList.remove('hidden');

    inputDocumento.addEventListener('focus', evt => {
        inputDocumento.value = '';
        feedbackdocumento.textContent = '';
    });

    tipoDocumento.addEventListener('change', evt => {
        inputDocumento.value = '';
        triggerKeyup(inputDocumento);
    });

    optionSelected = razonVisita.options[razonVisita.selectedIndex];

    razonVisita.addEventListener('change', evt => {
        //Mostrar checkbox All
        optionSelected = evt.target.options[evt.target.selectedIndex];
        if (optionSelected.classList.contains('free')) {
        }
        else {

        }
    });

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
                $('#autoComplete').val(''); // display the selected text
                html =
                    `
                <tr class="bg-white border-b">
                    <td class="p-2">
                        <div class="flex items-center text-sm">
                            <p class="font-medium">${ui.item.name}</p>
                        </div>
                    </td>
                    <td class="p-2">
                        <button class="flex items-center justify-between px-2 py-2 text-medium font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray" value='${ui.item.id}'>
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                `;

                listaVisitantes.innerHTML += html;
            }
            tipoDocumento.value = 'R';
            inputDocumento.value = '';
        }

    });


    closeAreas.forEach(e => {
        e.addEventListener('click', cerrarEditarVisitantes);
    });

    function cerrarEditarVisitantes(e) {
        modalAreas.classList.toggle('hidden');
        areasContent.classList.add('hidden');
        customersContent.classList.add('hidden');
        guardarAreas.removeEventListener('click', actualizarVisitantes);
        closeAreas.forEach(evt => {
            evt.removeEventListener('click', cerrarEditarVisitantes);
        });
    }

    guardarAreas.addEventListener('click', actualizarVisitantes);

    function actualizarVisitantes(evt) {
        let errores = {};

        let areasTempo = [];

        

        if (areasTempo.length == 0) {
            errores.areas = "Por favor, seleccione las áreas deseadas";
            feedbackareas.textContent = errores.areas;
        }

        if (Object.keys(errores).length == 0) {

            areasActualizar = Array.from(areasTempo);
            modalAreas.classList.toggle('hidden');
            guardarAreas.removeEventListener('click', actualizarAreas);
            closeAreas.forEach(e => {
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

}

//Metodo de evento para editar las areas visitadas
let areasActualizar = [];

function editarAreas(e) {

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

    //Cargar información inicial
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
    modalAreas.classList.toggle('hidden');
    areasContent.classList.remove('hidden');
    customersContent.classList.add('hidden');

    closeAreas.forEach(e => {
        e.addEventListener('click', cerrarEditarAreas);
    });

    function cerrarEditarAreas(e) {
        modalAreas.classList.toggle('hidden');
        guardarAreas.removeEventListener('click', actualizarAreas);
        closeAreas.forEach(evt => {
            evt.removeEventListener('click', cerrarEditarAreas);
        });
    }

    guardarAreas.addEventListener('click', actualizarAreas);

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
            modalAreas.classList.toggle('hidden');
            guardarAreas.removeEventListener('click', actualizarAreas);
            closeAreas.forEach(e => {
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
}


function interruptor(e) {

    let estado = 0;

    if (e.name == "activar") {
        estado = 1;
    }

    $.ajax({
        url: "./functions.php",
        type: "POST",
        datatype: "json",
        data: {
            solicitud: "d",
            id: e.value,
            status: estado
        },
        success: function (data) {
            tablaVisitas.ajax.reload();
            if (estado) {
                Toastify({
                    text: "Visita activada!",
                    duration: 3000,
                    style: {
                        background: '#10B981'
                    }
                }).showToast();
            }
            else {
                Toastify({
                    text: "Visita desactivada!",
                    duration: 3000,
                    style: {
                        background: '#EF4444'
                    }
                }).showToast();
            }
        }
    });

}

//Funcion para detectar cambio de valor en un campo incorrecto<-validate
function cambioValor(e) {
    e.target.nextElementSibling.textContent = '';
    e.target.removeEventListener('change', cambioValor);
}

function triggerKeyup(element) {
    let keyupEvent = new Event('keyup');
    element.dispatchEvent(keyupEvent);
}

function triggerChange(element) {
    let changeEvent = new Event('change');
    element.dispatchEvent(changeEvent);
}