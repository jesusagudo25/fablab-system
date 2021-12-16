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
    observacion = document.querySelector('textarea[name="observation"]'),
    fecha = document.querySelector('input[name="fecha"]'),
    registrar = document.querySelector('button[type="submit"]'),
        codigo = document.querySelector('input[name="codigo"]'),
    email = document.querySelector('input[name="email"]'),
    telefono = document.querySelector('input[name="telefono"]'),
    edad = document.querySelectorAll('input[name="edad"]'),
    sexo = document.querySelectorAll('input[name="sexo"]'),
    provincia = document.querySelector('select[name="provincia"]'),
    distrito = document.querySelector('select[name="distrito"]'),
    corregimiento = document.querySelector('select[name="corregimiento"]'),
    feedbackcorreo = document.querySelector('#feedbackcorreo'),
    feedbacktelefono = document.querySelector('#feedbacktelefono'),
    feedbackdocumento = document.querySelector('#feedbackdocumento'),
    feedbackcodigo = document.querySelector('#feedbackcodigo'),
    formulario = document.querySelector('form');

//Informacion inicial
let distritos = [];
let corregimientos = [];
let errores = {};

fetch('./functions.php',{
    method: "POST",
    mode: "same-origin",
    credentials: "same-origin",
    headers: {
        "Content-Type": "application/json"
    },
    body: JSON.stringify({datos: {
            solicitud: "d",
        }})
})
    .then(res => res.json())
    .then(data =>{
        distritos = data;

        const resul = distritos.filter(x => x.province_id == provincia.value);
        resul.forEach(e => {
            if(e.name == 'Santiago'){
                distrito.innerHTML += `<option value="${e.district_id}" selected>${e.name}</option>`;
            }
            else{
                distrito.innerHTML += `<option value="${e.district_id}" >${e.name}</option>`;
            }
        });

        fetch('./functions.php',{
            method: "POST",
            mode: "same-origin",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({datos: {
                    solicitud: "c",
                }})
        })
            .then(res => res.json())
            .then(data =>{
                corregimientos = data;

                const resul = corregimientos.filter(x => x.district_id == distrito.value);
                resul.forEach(e => {
                    if(e.name == 'Santiago'){
                        corregimiento.innerHTML += `<option value="${e.township_id}" selected>${e.name}</option>`;
                    }
                    else{
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
    feedbackdocumento.textContent='';
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
                    response(data);
                }
            });
        },
        select: function (event, ui) {
            $('#autoComplete').val(ui.item.label); // display the selected text
            idHidden.value = ui.item.id;

            if(ui.item.code){
                codigo.value = ui.item.code;
            }
            else{
                codigo.placeholder = 'Sin código asignado';
            }

            codigo.disabled = true;
            codigo.classList.add('bg-gray-300');
            codigo.classList.add('cursor-not-allowed');
            nombreUsuario.value = ui.item.name;
            nombreUsuario.disabled = true;
            nombreUsuario.classList.add('bg-gray-300');
            nombreUsuario.classList.add('cursor-not-allowed');

            if(ui.item.email){
                email.value = ui.item.email;
            }
            else{
                email.placeholder = 'Sin correo electrónico asignado';
            }

            email.disabled = true;
            email.classList.add('bg-gray-300');
            email.classList.add('cursor-not-allowed');

            if(ui.item.telephone){
                telefono.value = ui.item.telephone;
            }
            else{
                telefono.placeholder = 'Sin teléfono asignado';
            }

            telefono.disabled = true;
            telefono.classList.add('bg-gray-300');
            telefono.classList.add('cursor-not-allowed');
            //edad
            let edadChecked = Array.from(edad).find( x => x.value == ui.item.age_range);
            edadChecked.checked = true;
            edad.forEach( e =>{
                e.disabled = true;
                e.classList.remove('text-blue-600')
                e.classList.add('cursor-not-allowed', 'text-gray-500');
            })
            //sexo
            let sexoChecked = Array.from(sexo).find( x => x.value == ui.item.sex);
            sexoChecked.checked = true;
            sexo.forEach( e =>{
                e.disabled = true;
                e.classList.remove('text-blue-600')
                e.classList.add('cursor-not-allowed', 'text-gray-500');
            })

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

            distrito.innerHTML = '';
            let items = distritos.filter(x => x.province_id == provincia.value);
            items.forEach(e => {
                if(e.district_id == ui.item.district){
                distrito.innerHTML += `<option value="${e.district_id}" selected>${e.name}</option>`;
                }
                else{

                distrito.innerHTML += `<option value="${e.district_id}">${e.name}</option>`;
                }
            });

            //corregim
            corregimiento.innerHTML = '';
            items = corregimientos.filter(x => x.district_id == distrito.value);
            items.forEach(e => {
                if (e.township_id == ui.item.township){
                corregimiento.innerHTML += `<option value="${e.township_id}">${e.name}</option>`;

                }
                else{
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
            accion.classList.remove('bg-green-500', 'active:bg-green-600', 'hover:bg-green-700');
            accion.classList.add('bg-yellow-500', 'active:bg-yellow-600', 'hover:bg-yellow-700');

            return false;

        }

    });


});

function triggerKeyup(element){
    let keyUpEvent = new Event('keyup');
    element.dispatchEvent(keyUpEvent);
}

//Dependiendo la razon social (NOTFREE, FREE) aparecera la seccion de area de trabajo
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

function triggerBlur(element){
    let blurEvent = new Event('blur');
    element.dispatchEvent(blurEvent);
}

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
            style: {
                background: '#10B981'
            }
        }).showToast();

        inputDocumento.addEventListener('blur', validarDocumento);
        codigo.addEventListener('blur', validarCodigo);
        email.addEventListener('blur', validarEmail);
        telefono.addEventListener('blur', validarTelefono);

        triggerBlur(inputDocumento);
    }
    else if(evt.currentTarget.children[0].classList.contains('fa-user-times')){
        containerRegister.classList.add('hidden');
        evt.currentTarget.innerHTML = '<i class="fas fa-user-plus"></i>';
        evt.currentTarget.classList.remove('bg-red-500', 'active:bg-red-600', 'hover:bg-red-700');
        evt.currentTarget.classList.add('bg-green-500', 'active:bg-green-600', 'hover:bg-green-700');

        restore();
        inputDocumento.removeEventListener('blur', validarDocumento);
        codigo.removeEventListener('blur', validarCodigo);
        email.removeEventListener('blur', validarEmail);
        telefono.removeEventListener('blur', validarTelefono);
        feedbackdocumento.textContent = '';
        feedbackcodigo.textContent = '';
        feedbackcorreo.textContent = '';
        feedbacktelefono.textContent = '';
    }
    else if(evt.currentTarget.children[0].classList.contains('fa-eye')){
        containerRegister.classList.remove('hidden');
        evt.currentTarget.innerHTML = '<i class="fas fa-eye-slash"></i>';
        evt.currentTarget.classList.remove('bg-yellow-500', 'active:bg-yellow-600', 'hover:bg-yellow-700');
        evt.currentTarget.classList.add('bg-red-500', 'active:bg-red-600', 'hover:bg-red-700');
        Toastify({
            text: "Datos del cliente",
            duration: 3000,
            style: {
                background: '#10B981'
            }
        }).showToast();
    }
    else if(evt.currentTarget.children[0].classList.contains('fa-eye-slash')){
        containerRegister.classList.add('hidden');
        evt.currentTarget.innerHTML = '<i class="fas fa-eye"></i>';
        evt.currentTarget.classList.remove('bg-red-500', 'active:bg-red-600', 'hover:bg-red-700');
        evt.currentTarget.classList.add('bg-yellow-500', 'active:bg-yellow-600', 'hover:bg-yellow-700');
    }
});

function validarDocumento(e){
    fetch('./functions.php',{
        method: "POST",
        mode: "same-origin",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({datos: {
                solicitud: 'doc',
                documento: e.target.value,
            }})
    })
        .then(res => res.json())
        .then(data =>{
            if(data == true){
                errores.documento = 'El documento ya esta registrado';
                feedbackdocumento.textContent = errores.documento;
            }
            else{
                if('documento' in errores){
                    delete errores.documento;
                }
                feedbackdocumento.textContent = '';
            }
        });

}

function validarCodigo(e){
    fetch('./functions.php',{
        method: "POST",
        mode: "same-origin",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({datos: {
                solicitud: 'cod',
                codigo: e.target.value,
            }})
    })
        .then(res => res.json())
        .then(data =>{
            if(data == true){
                errores.codigo = 'El codigo ya esta registrado';
                feedbackcodigo.textContent = errores.codigo;
            }
            else{
                if('codigo' in errores){
                    delete errores.codigo;
                }
                feedbackcodigo.textContent = '';
            }
        });

}

function validarEmail(e){
    fetch('./functions.php',{
        method: "POST",
        mode: "same-origin",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({datos: {
                solicitud: 'cor',
                email: e.target.value,
            }})
    })
        .then(res => res.json())
        .then(data =>{
            if(data == 'true'){
                errores.correo = 'El correo ya esta registrado';
                feedbackcorreo.textContent = errores.correo;
            }
            else if(data == 'validate'){
                errores.correo = 'Por favor, proporcione un correo valido';
                feedbackcorreo.textContent = errores.correo;
            }
            else{
                if('correo' in errores){
                    delete errores.correo;
                }
                feedbackcorreo.textContent = '';
            }
        });

}

function validarTelefono(e){
    fetch('./functions.php',{
        method: "POST",
        mode: "same-origin",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({datos: {
                solicitud: 'tel',
                telefono: e.target.value,
            }})
    })
        .then(res => res.json())
        .then(data =>{
            if(data == true){
                errores.telefono = 'El telefono ya esta registrado';
                feedbacktelefono.textContent = errores.telefono;
            }
            else{
                if('telefono' in errores){
                    delete errores.telefono;
                }
                feedbacktelefono.textContent = '';
            }
        });

}

inputDocumento.addEventListener('keyup', evt => {

    if(evt.key != "Enter"){
        if(evt.target.value != ''){
            accion.classList.remove('hidden');
            if(!containerRegister.classList.contains('hidden') || accion.classList.contains('bg-yellow-500')){
                restore();
            }
        }
        else{
            accion.classList.add('hidden');
            restore();
        }
        inputDocumento.removeEventListener('blur', validarDocumento);
        codigo.removeEventListener('blur', validarCodigo);
        email.removeEventListener('blur', validarEmail);
        telefono.removeEventListener('blur', validarTelefono);
        feedbackdocumento.textContent = '';
        feedbackcodigo.textContent = '';
        feedbackcorreo.textContent = '';
        feedbacktelefono.textContent = '';
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
    codigo.placeholder= 'Ingrese el codigo de cliente CIDETE';
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
    email.placeholder= 'Ingrese el correo electrónico del cliente';
    telefono.disabled = false;
    telefono.value = '';
    telefono.classList.remove('bg-gray-300');
    telefono.classList.remove('cursor-not-allowed');
    telefono.placeholder= 'Ingrese el número de telefono del cliente';

    //edad
    let resul = Array.from(edad).find( x => x.checked);
    if(resul){
        resul.checked = false;
    }
    edad.forEach( e =>{
        e.disabled = false;
        e.classList.remove('text-gray-500','cursor-not-allowed')
        e.classList.add('text-blue-600');
    })


    //sexo
    resul = Array.from(sexo).find( x => x.checked);
    if(resul){
        resul.checked = false;
    }
    sexo.forEach( e =>{
        e.disabled = false;
        e.classList.remove('text-gray-500','cursor-not-allowed')
        e.classList.add('text-blue-600');
    })


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

    distrito.innerHTML = '';
    let item = distritos.filter(x => x.province_id == provincia.value);
    item.forEach(e => {
        if (e.name == 'Santiago') {
            distrito.innerHTML += `<option value="${e.district_id}" selected>${e.name}</option>`;
        } else {
            distrito.innerHTML += `<option value="${e.district_id}" >${e.name}</option>`;
        }
    });

    corregimiento.innerHTML = '';
    item = corregimientos.filter(x => x.district_id == distrito.value);
    item.forEach(e => {
        if(e.name == 'Santiago'){
            corregimiento.innerHTML += `<option value="${e.township_id}" selected>${e.name}</option>`;
        }
        else{
            corregimiento.innerHTML += `<option value="${e.township_id}">${e.name}</option>`;
        }
    });

    feeds.forEach(x => {
        x.textContent = '';
    })
}