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
            "targets": 3
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
            "targets": 4
        },
        {
            "data": null,
            render: function (data, type, row) {
                return '<div class="flex gap-2"><a href="./show_visitors.php?visit_id='+data[0]+'" type="button" class="flex items-center justify-between px-2 py-2 text-lg font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray btn-editar" onclick="editarVisita(this)"><i class="fas fa-users"></i></a> <div class="flex gap-2"><a href="./show_areas.php?visit_id='+data[0]+'" type="button" class="flex items-center justify-between px-2 py-2 text-lg font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray btn-editar" onclick="editarVisita(this)"><i class="fas fa-clipboard-check"></i></a> <button value="' + data[0] + '" type="button" class="flex items-center justify-between px-2 py-2 text-lg font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray btn-editar" onclick="editarVisita(this)"><i class="fas fa-edit"></i></button></div>';
            },
            "targets": 5
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

            const fechaVisita = document.querySelector('input[name="fecha"]'),
                razonVisita = document.querySelector('select[name="razonvisita"]'),
                observacion = document.querySelector('textarea[name="observacion"]');

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