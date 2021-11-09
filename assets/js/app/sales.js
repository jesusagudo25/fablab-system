const tipoDocumento = document.querySelector('select[name="tipodocumento"]'),
    tituloDocumento=  document.querySelector('#tituloDocumento'),
    inputDocumento = document.querySelector('input[name="documento"]'),
    idHidden = document.querySelector('input[type="hidden"]'),
    agregar = document.querySelector('#agregar'),
    registrar = document.querySelector('input[type="submit"]'),
    fecha = document.querySelector('input[name="fecha"]'),
    categoria_servicio = document.querySelector('select[name="categoria_servicio"]'),
    servicio = document.querySelector('select[name="servicio"]'),
    nombreCliente = document.querySelector('input[name="name"]');

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
        servicios['areas'].forEach( (e) =>{
                servicio.innerHTML += `<option value="${e.id}" >${e.name}</option>`;
        });

    });

//Cambio de categoria servicio
categoria_servicio.addEventListener('change', evt => {

    servicio.innerHTML = '';
    optionSelected = evt.target.options[evt.target.selectedIndex];
    servicios[optionSelected.value].forEach( (e) =>{
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

});

let id_cliente = 0;
let producto_ag = [];
let contandorF = 0;
let html = '';

let sw =0;

function registrarProducto(id_categoria_servicio, descripcion, precio) {

    if(sw === 0 ){
        sw=1;
        document.querySelector('#acciones').classList.remove('hidden');
    }

    contandorF++;
    if (document.getElementById('cod_producto').value != '') {
            if(producto_ag.indexOf(id_categoria_servicio) === -1){
                producto_ag.push(id_categoria_servicio);

                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Producto Ingresado!',
                    showConfirmButton: false,
                    timer: 1500
                })

                html+=`
                <tr class="text-gray-700 dark:text-gray-400" id="${id_categoria_servicio}">
                    <td class="px-4 py-3">
                      <div class="flex items-center text-sm">
                          <p class="font-semibold">${descripcion.substring(0,20)}</p>
                      </div>
                    </td>
                    <td class="px-4 py-3 text-sm">
                    ${precio}
                    </td>
                    <td class="px-4 py-3 text-sm">
                    <input type="number" class="dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" value="1" min="1" max="4" onchange="cambiarTotal(this,${contandorF},${precio})">
                    </td>
                    <td class="px-4 py-3 text-sm font-semibold" id="${contandorF}">
                      ${precio}
                    </td>
                    <td class="px-4 py-3">
                      <div class="flex items-center space-x-4 text-sm">
                        <button
                          class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                          aria-label="Delete"
                          onclick="deleteProducto(${id_categoria_servicio})"
                        >
                          <svg
                            class="w-5 h-5"
                            aria-hidden="true"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                          >
                            <path
                              fill-rule="evenodd"
                              d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                              clip-rule="evenodd"
                            ></path>
                          </svg>
                        </button>
                      </div>
                    </td>
                  </tr>
                `
                document.querySelector("#cod_producto").value = '';
                document.querySelector("#cod_producto").focus();

                document.querySelector("#detalle_venta").innerHTML = html;
                calcular();
            }
            else{
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Ya ha sido ingresado!',
                    showConfirmButton: false,
                    timer: 1500
                })

                document.querySelector("#cod_producto").value = '';
                document.querySelector("#cod_producto").focus();
            }

    }
}