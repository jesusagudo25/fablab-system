//###########Form input##########

const tipoDocumento = document.querySelector('select[name="tipodocumento"]'),
     tituloDocumento=  document.querySelector('#tituloDocumento'),
    inputDocumento = document.querySelector('input[name="documento"]'),
    nombreUsuario = document.querySelector('input[name="name"]'),
    areasTrabajo = document.querySelectorAll('input[type="checkbox"]'),
    razonVisita = document.querySelector('select[name="razonvisita"]'),
    containerArea = document.querySelector('#containerarea'),
    idHidden = document.querySelector('input[type="hidden"]'),
    accion = document.querySelector('#action'),
    containerRegister = document.querySelector('#containerregister'),
        registrar = document.querySelector('input[type="submit"]'),
    observacion = document.querySelector('textarea[name="observation"]'),
    fecha = document.querySelector('input[name="fecha"]'),
    feedbackdocumento = document.querySelector('#feedbackdocumento'),
        codigo = document.querySelector('input[name="codigo"]'),
    email = document.querySelector('input[name="email"]'),
    telefono = document.querySelector('input[name="telefono"]'),
    provincia = document.querySelector('select[name="provincia"]'),
    distrito = document.querySelector('select[name="distrito"]'),
    corregimiento = document.querySelector('select[name="corregimiento"]');

//Informacion inicial

function cargarDistritoCorregimiento() {
    distrito.innerHTML = '';
    fetch('./functions.php',{
        method: "POST",
        mode: "same-origin",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({datos: {
                solicitud: "d",
                id: provincia.value
            }})
    })
        .then(res => res.json())
        .then(data =>{
            data.forEach(e=>{
                if(e.name == 'Santiago'){
                    distrito.innerHTML += `<option value="${e.district_id}" selected>${e.name}</option>`;
                }
                else{
                    distrito.innerHTML += `<option value="${e.district_id}" >${e.name}</option>`;
                }


            })
            corregimiento.innerHTML = '';
            fetch('./functions.php',{
                method: "POST",
                mode: "same-origin",
                credentials: "same-origin",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({datos: {
                        solicitud: "c",
                        id: distrito.value
                    }})
            })
                .then(res => res.json())
                .then(data =>{
                    data.forEach(e=>{
                        if(e.name == 'Santiago'){
                            corregimiento.innerHTML += `<option value="${e.township_id}" selected >${e.name}</option>`;
                        }
                        else{
                            corregimiento.innerHTML += `<option value="${e.township_id}" >${e.name}</option>`;
                        }

                    })
                });
        });

}

cargarDistritoCorregimiento();


//Algoritmo Provincia-Distrito-Corregimiento

provincia.addEventListener('change', evt => {

    distrito.innerHTML = '';
    fetch('./functions.php',{
        method: "POST",
        mode: "same-origin",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({datos: {
                solicitud: "d",
                id: evt.target.value
            }})
    })
        .then(res => res.json())
        .then(data =>{
            data.forEach(e=>{
                distrito.innerHTML += `<option value="${e.district_id}" >${e.name}</option>`;
            })
            triggerChange(distrito);
        });

});

distrito.addEventListener('change', evt => {

    corregimiento.innerHTML = '';
    fetch('./functions.php',{
        method: "POST",
        mode: "same-origin",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({datos: {
                solicitud: "c",
                id: evt.target.value
            }})
    })
        .then(res => res.json())
        .then(data =>{
            data.forEach(e=>{
                corregimiento.innerHTML += `<option value="${e.township_id}" >${e.name}</option>`;
            })
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

//Se realiza un autocomplete buscando el cliente, en caso de no encontrase aparecera un signo de (+) para agregarlo

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
                    response(data.slice(0, 3));
                }
            });
        },
        select: function (event, ui) {
            $('#autoComplete').val(ui.item.label); // display the selected text
            idHidden.value = ui.item.id;
            codigo.value = ui.item.code;
            codigo.disabled = true;
            codigo.classList.add('bg-gray-300');
            codigo.classList.add('cursor-not-allowed');
            nombreUsuario.value = ui.item.name;
            nombreUsuario.disabled = true;
            nombreUsuario.classList.add('bg-gray-300');
            nombreUsuario.classList.add('cursor-not-allowed');
            email.value = ui.item.email;
            email.disabled = true;
            email.classList.add('bg-gray-300');
            email.classList.add('cursor-not-allowed');
            telefono.value = ui.item.telephone;
            telefono.disabled = true;
            telefono.classList.add('bg-gray-300');
            telefono.classList.add('cursor-not-allowed');
            //provincia
            Array.from(provincia.options).forEach( (opt) =>{
                if(opt.value == ui.item.province){
                    opt.selected = true;
                }
            });

            provincia.disabled = true;
            provincia.classList.add('bg-gray-300');
            provincia.classList.add('cursor-not-allowed');
            //distrito
            //distrito.innerHTML= `<option value="${ui.item.district}" >${e.name}</option>`;
            distrito.innerHTML = '';
            fetch('./functions.php',{
                method: "POST",
                mode: "same-origin",
                credentials: "same-origin",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({datos: {
                        solicitud: "d",
                        id: provincia.value
                    }})
            })
                .then(res => res.json())
                .then(data =>{
                    data.forEach(e=>{
                        distrito.innerHTML += `<option value="${e.district_id}" >${e.name}</option>`;
                    })
                    Array.from(distrito.options).forEach( (opt) =>{
                        if(opt.value == ui.item.district){
                            opt.selected = true;
                        }
                    });
//---------------------------------
                    corregimiento.innerHTML = '';
                    fetch('./functions.php',{
                        method: "POST",
                        mode: "same-origin",
                        credentials: "same-origin",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({datos: {
                                solicitud: "c",
                                id: distrito.value
                            }})
                    })
                        .then(res => res.json())
                        .then(data =>{
                            data.forEach(e=>{
                                corregimiento.innerHTML += `<option value="${e.township_id}" >${e.name}</option>`;
                            })
                            Array.from(corregimiento.options).forEach( (opt) =>{
                                if(opt.value == ui.item.township){
                                    opt.selected = true;
                                }
                            });
                        });
                });

            distrito.disabled = true;
            distrito.classList.add('bg-gray-300');
            distrito.classList.add('cursor-not-allowed');

            //corregimiento
            //corregimiento.innerHTML= `<option value="${ui.item.township}" >${e.name}</option>`;
            corregimiento.disabled = true;
            corregimiento.classList.add('bg-gray-300');
            corregimiento.classList.add('cursor-not-allowed');

            Toastify({
                text: "Visitante seleccionado",
                duration: 3000,
                backgroundColor: "#10B981",
            }).showToast();

            accion.innerHTML = '<i class="fas fa-eye"></i>';
            accion.classList.remove('bg-green-500', 'active:bg-green-600', 'hover:bg-green-700');
            accion.classList.add('bg-yellow-500', 'active:bg-yellow-600', 'hover:bg-yellow-700');

            return false;

        }

    });


});

function triggerChange(element){
    let changeEvent = new Event('change');
    element.dispatchEvent(changeEvent);
}

function triggerKeyup(element){
    let changeEvent = new Event('keyup');
    element.dispatchEvent(changeEvent);
}

//Dependiendo la razon social (NOTFREE, FREE) aparecera la seccion de area de trabajo9-122-3923
let optionSelected = razonVisita.options[razonVisita.selectedIndex];

razonVisita.addEventListener('change', evt => {
    optionSelected = evt.target.options[evt.target.selectedIndex];
    if(optionSelected.classList.contains('free')){
        containerArea.classList.add('hidden');
    }
    else{
        containerArea.classList.remove('hidden');
    }
});

//Dependiendo del checkbox seleccionado aparecera su hora de inicio y salida.

areasTrabajo.forEach(evt =>{
    evt.addEventListener('click', item =>{
        const areaCheck = document.querySelector('#area'+item.target.value);
        if(areaCheck.nextElementSibling){areaCheck.nextElementSibling.classList.toggle('mt-4');}
        areaCheck.classList.toggle('hidden');
    });
});

//Button action

accion.addEventListener('click',evt => {

   //Se puede aplicar paradigma de objetos...

    if(evt.currentTarget.children[0].classList.contains('fa-user-plus')){
        containerRegister.classList.remove('hidden');
        evt.currentTarget.innerHTML = '<i class="fas fa-user-times"></i>';
        evt.currentTarget.classList.remove('bg-green-500', 'active:bg-green-600', 'hover:bg-green-700');
        evt.currentTarget.classList.add('bg-red-500', 'active:bg-red-600', 'hover:bg-red-700');

        Toastify({
            text: "Se registrará un nuevo cliente",
            duration: 3000,
            backgroundColor: "#10B981",
        }).showToast();

    }
    else if(evt.currentTarget.children[0].classList.contains('fa-user-times')){
        containerRegister.classList.add('hidden');
        evt.currentTarget.innerHTML = '<i class="fas fa-user-plus"></i>';
        evt.currentTarget.classList.remove('bg-red-500', 'active:bg-red-600', 'hover:bg-red-700');
        evt.currentTarget.classList.add('bg-green-500', 'active:bg-green-600', 'hover:bg-green-700');
    }
    else if(evt.currentTarget.children[0].classList.contains('fa-eye')){
        containerRegister.classList.remove('hidden');
        evt.currentTarget.innerHTML = '<i class="fas fa-eye-slash"></i>';
        evt.currentTarget.classList.remove('bg-yellow-500', 'active:bg-yellow-600', 'hover:bg-yellow-700');
        evt.currentTarget.classList.add('bg-red-500', 'active:bg-red-600', 'hover:bg-red-700');
        Toastify({
            text: "Datos del cliente",
            duration: 3000,
            backgroundColor: "#10B981",
        }).showToast();
    }
    else if(evt.currentTarget.children[0].classList.contains('fa-eye-slash')){
        containerRegister.classList.add('hidden');
        evt.currentTarget.innerHTML = '<i class="fas fa-eye"></i>';
        evt.currentTarget.classList.remove('bg-red-500', 'active:bg-red-600', 'hover:bg-red-700');
        evt.currentTarget.classList.add('bg-yellow-500', 'active:bg-yellow-600', 'hover:bg-yellow-700');
    }
});

inputDocumento.addEventListener('keyup', evt => {

    if(evt.target.value.length == 1){
        restore();
    }

    if(evt.target.value != ''){
        accion.classList.remove('hidden');
    }
    else{
        accion.classList.add('hidden');
        restore();

    }
});

function restore() {
    containerRegister.classList.add('hidden');
    idHidden.value = '';
    accion.innerHTML = '<i class="fas fa-user-plus"></i>';
    accion.classList.remove('bg-red-500', 'active:bg-red-600', 'hover:bg-red-700');
    accion.classList.remove('bg-yellow-500', 'active:bg-yellow-600', 'hover:bg-yellow-700');
    accion.classList.add('bg-green-500', 'active:bg-green-600', 'hover:bg-green-700');


    codigo.disabled = false;
    codigo.value = '';
    codigo.classList.remove('bg-gray-300');
    codigo.classList.remove('cursor-not-allowed');
    nombreUsuario.disabled = false;
    nombreUsuario.value = '';
    nombreUsuario.classList.remove('bg-gray-300');
    nombreUsuario.classList.remove('cursor-not-allowed');
    email.disabled = false;
    email.value = '';
    email.classList.remove('bg-gray-300');
    email.classList.remove('cursor-not-allowed');
    telefono.disabled = false;
    telefono.value = '';
    telefono.classList.remove('bg-gray-300');
    telefono.classList.remove('cursor-not-allowed');
    provincia.classList.remove('bg-gray-300');
    provincia.classList.remove('cursor-not-allowed');
    provincia.disabled = false;
    distrito.classList.remove('bg-gray-300');
    distrito.classList.remove('cursor-not-allowed');
    distrito.disabled = false;
    corregimiento.classList.remove('bg-gray-300');
    corregimiento.classList.remove('cursor-not-allowed');
    corregimiento.disabled = false;

    //provincia.value = ui.item.province;
    Array.from(provincia.options).forEach( (opt) =>{
        if(opt.text == 'Veraguas'){
            opt.selected = true;
        }
    });

    cargarDistritoCorregimiento();
}