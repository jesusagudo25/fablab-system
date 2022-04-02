const action = document.querySelector('#action'),
    receipt = document.querySelector('input[name="numero_recibo"]'),
    modal = document.querySelector('#modal'),
    closeModal = document.querySelector('#close'),
    modal_content = document.querySelector('#modal-content');

const TIPO_TABLAS = {
    "membresias": (data) => {
        modal_content.innerHTML = `
            <main class="grid grid-cols-2 gap-5">
                <label class="text-sm block">
                    <span class="text-gray-800 font-medium">Descripción</span>
                    <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="text" value="${data.name}" disabled>
                </label>
                <label class="text-sm block">
                    <span class="text-gray-800 font-medium">Precio</span>
                    <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="number" value="${data.price}" disabled>
                </label>
                    <label class="text-sm block">
                        <span class="text-gray-800 font-medium">Fecha inicial</span>
                        <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="date" value="${data.initial_date}" disabled>
                    </label>
                    <label class="text-sm block">
                        <span class="text-gray-800 font-medium">Fecha final</span>
                        <input class="mt-1 text-sm  w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="date" value="${data.final_date}" disabled>
                    </label>
            </main>
                `;

        inputFechaFinal = document.querySelector('input[name="fecha_final"]');
    },
    "eventos": (data) => {
        modal_content.innerHTML = `
        <main class="grid grid-cols-1 gap-5 justify-items-center">                      
            <div class="w-full grid grid-cols-2 gap-5">
                <label class="text-sm block">
                    <span class="text-gray-800 font-medium">Nombre</span>
                    <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm bg-gray-300 cursor-not-allowed" type="text" placeholder="Ingrese el nombre del evento" name="nombre_evento" value="${data.name}" disabled>
                </label>
                    
                <label class="text-sm block">
                    <span class="text-gray-800 font-medium">Precio</span>
                    <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm bg-gray-300 cursor-not-allowed" type="number" placeholder="Precio de evento" name="precio_evento" min="0.00" step="0.01" value="${data.price}" disabled="">
                </label>

                <label class="text-sm block">
                    <span class="text-gray-800 font-medium">Área</span>
                    <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm bg-gray-300 cursor-not-allowed" required name="area" disabled>
                        <option value="${data.area_name}" selected>${data.area_name}</option>
                    </select>
                </label>

                <label class="text-sm block">
                    <span class="text-gray-800 font-medium">Categoría</span>
                    <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm bg-gray-300 cursor-not-allowed" required name="categoria" disabled>
                        <option value="${data.category_name}" selected>${data.category_name}</option>
                    </select>
                </label>
        
                <label class="text-sm block">
                    <span class="text-gray-800 font-medium">Fecha inicial</span>
                    <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm bg-gray-300 cursor-not-allowed" type="date" name="fecha_inicial" value="${data.initial_date}" disabled="">
                </label>

                <label class="text-sm block">
                    <span class="text-gray-800 font-medium">Fecha final</span>
                    <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm bg-gray-300 cursor-not-allowed" type="date" name="fecha_final" value="${data.final_date}" disabled="">
                </label>

                <label class="text-sm block">
                    <span class="text-gray-800 font-medium">Hora inicial</span>
                    <input type="time" name="hora_inicial" class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm bg-gray-300 cursor-not-allowed" value="${data.start_time}" disabled>
                </label>

                <label class="text-sm block">
                    <span class="text-gray-800 font-medium">Hora final</span>
                    <input type="time" name="hora_final" class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm bg-gray-300 cursor-not-allowed" value="${data.end_time}" disabled>
                </label>
            </div>
        </main>`;
    },
    "areas": (data) => {
        modal_content.innerHTML = `<main class="grid grid-cols-2 gap-5">
                    <label class="col-span-full text-sm block">
                        <span class="text-gray-800 font-medium">Tipo de consumible</span>
                        <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required="" name="tipo_consumible""></select>
                    </label>

                    <label class="text-sm block">
                        <span class="text-gray-800 font-medium">Unidad</span>
                        <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" placeholder="Ingrese la cantidad de" name="cantidad_unidad" value="" min="0" >
                    </label>

                    <label class="text-sm block">
                        <span class="text-gray-800 font-medium">Precio unitario</span>
                        <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300" type="number" name="precio_unitario" placeholder="Ingrese el precio unitario" min="0.00" step="0.01">
                    </label>

                    <label class="text-sm block">
                        <span class="text-gray-800 font-medium">Tiempo impresión (minutos)</span>
                        <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" type="number" placeholder="Ingrese la cantidad de minutos" name="cantidad_tiempo" value="" min="0" >
                    </label>

                    <label class="text-sm block">
                        <span class="text-gray-800 font-medium">Precio impresión</span>
                        <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300" type="number" name="precio_impresion" 
                        placeholder="Ingrese el precio de impresión"
                        value="0.05" min="0.00" step="0.01">
                    </label>

                    <label class="col-span-full text-sm block">
                        <span class="text-gray-800 font-medium">Costo base</span>
                        <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="number" name="costo_base" value="" disabled>
                    </label>
                    <label class="col-span-full text-sm block">
                        <span class="text-gray-800 font-medium">Porcentaje ganancia</span>
                        <select class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" name="tipo_ganancia">
                        <option value="0.15" >Estudiante - 15%</option>
                        <option value="0.2">Docente - 20%</option>
                        <option value="0.3" selected>Público - 30%</option>
                        </select>
                    </label>

                    <label class="col-span-full text-sm block">
                        <span class="text-gray-800 font-medium">Costo total</span>
                        <input class="mt-1 text-sm w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-gray-300 cursor-not-allowed" type="number" name="costo_total" value="" disabled>
                    </label>           
                    </main>`;
    } //Por terminar
};

if (action) {
    action.addEventListener('click', function () {
        if (receipt.value == '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Ingrese un número de recibo',
                confirmButtonColor: '#3b82f6',
            });
        } else {
            const response = $.post('./functions.php', {
                receipt: receipt.value,
                invoice_id: action.value,
                solicitud: 'up_receipt'
            });
            response.done(function (data) {
                const p = document.createElement('p');
                const span = document.createElement('span');
                span.classList.add('text-gray-800','font-semibold');
                span.textContent = 'Recibo UP:';
                p.appendChild(span);
                p.innerHTML += ` # ${receipt.value}`;
                receipt.parentElement.parentElement.replaceWith(p);
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'El número de recibo ingresado existe',
                    confirmButtonColor: '#3b82f6'
                });
            });
        }
    });
}

function getDetails(e, service) {
    const id = e.getAttribute('value');

    const response = $.post('./functions.php', {
        id: id,
        service: service,
        solicitud: 'get_details'
    });
    response.done(function (data) {
        TIPO_TABLAS[service](data);

        modal.classList.toggle('hidden');
        closeModal.addEventListener('click', cerrarDetalles);
    });
}

function cerrarDetalles(e) {
    modal.classList.toggle('hidden');
    e.target.removeEventListener('click', cerrarDetalles);
}