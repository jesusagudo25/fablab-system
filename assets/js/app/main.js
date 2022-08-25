//###########Form input##########

const tipoDocumento = document.querySelector('select[name="tipodocumento"]'),
     tituloDocumento=  document.querySelector('#tituloDocumento'),
    inputDocumento = document.querySelector('input[name="documento"]'),
    nombreUsuario = document.querySelector('input[name="name"]'),
    areasTrabajo = document.querySelectorAll('input[type="checkbox"]'),
    razonVisita = document.querySelector('select[name="razonvisita"]'),
    containerArea = document.querySelector('#containerarea'),
    containerIndividual = document.querySelector('#container_individual'),
    containerGrupal = document.querySelector('#container_grupal'),
    idHidden = document.querySelector('input[type="hidden"]'),
    accion = document.querySelector('#action'),
    booking = document.querySelector('#booking'),
    btnTypeVisit = document.querySelectorAll('input[name="typevisit"]'),
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

//Seleccione el tipo de visita

btnTypeVisit.forEach(e => {
    e.addEventListener('click', evt => {
        if(e.value == 'I'){
            console.log('Ingreso');
            containerIndividual.classList.remove('hidden');
            containerGrupal.classList.add('hidden');
        }
        else{
            console.log('Salida');
            containerIndividual.classList.add('hidden');
            containerGrupal.classList.remove('hidden');
        }
    } );
} );

//Tipo de documento -> RUC/CEDULA/PASAPORTE
const TIPOS_DOCUMENTOS = {
    C: (tituloDocumento,inputDocumento) => {
        tituloDocumento.textContent = 'Número de cédula';
        inputDocumento.placeholder = "Ingrese el número de cédula con guiones";
    },
    R: (tituloDocumento,inputDocumento) => {
        tituloDocumento.textContent = 'Número de RUC';
        inputDocumento.placeholder = "Ingrese el número de RUC con guiones";
    },
    P: (tituloDocumento,inputDocumento) => {
        tituloDocumento.textContent = 'Número de Pasaporte';
        inputDocumento.placeholder = "Ingrese el número de pasaporte con guiones";
    }
}

tipoDocumento.addEventListener('change', evt => {
    TIPOS_DOCUMENTOS[evt.target.value](tituloDocumento,inputDocumento);
    inputDocumento.value = '';
    feedbackdocumento.textContent='';
    triggerKeyup(inputDocumento)
});

//Se realiza un autocomplete buscando el cliente, en caso de no encontrase aparecera un signo de (+) para agregarlo

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
                    var result = { value:"0",label:"No se han encontrado resultados" };
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
        else{
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
            let provinceSelect = Array.from(provincia.options).find(opt => opt.value == ui.item.province);
            provinceSelect.selected = true;

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
            accion.classList.remove('bg-emerald-500', 'active:bg-emerald-600', 'hover:bg-emerald-700');
            accion.classList.add('bg-yellow-500', 'active:bg-yellow-600', 'hover:bg-yellow-700');
        }
    }
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
        containerTiempo.classList.remove('hidden');
        areasTrabajo.forEach(x => {
            x.checked = false;
            const areaCheck = document.querySelector('#area'+x.value);
            if(areaCheck.nextElementSibling.classList.contains('feed')){
                areaCheck.parentElement.nextElementSibling.classList.add('mt-5');
            }
            else{
                areaCheck.nextElementSibling.classList.add('mt-4');
            }
            document.querySelector('#area'+x.value).classList.add('hidden');
            document.querySelector('input[name="arrival_time_area'+x.value+'"]').value = '';
            document.querySelector('input[name="departure_time_area'+x.value+'"]').value = '';
    
        });
    }
    else{
        containerArea.classList.remove('hidden');
        containerTiempo.classList.add('hidden');
    }

});

//Dependiendo del checkbox seleccionado aparecera su hora de inicio y salida.

areasTrabajo.forEach(evt =>{
    evt.addEventListener('click', x =>{
        const areaCheck = document.querySelector('#area'+x.target.value);
        if(areaCheck.nextElementSibling.classList.contains('feed')){
            areaCheck.parentElement.nextElementSibling.classList.toggle('mt-5');
        }
        else{
            areaCheck.nextElementSibling.classList.toggle('mt-4');
        }
        areaCheck.classList.toggle('hidden');
        document.querySelector('input[name="arrival_time_area'+x.target.value+'"]').value = '';
        document.querySelector('input[name="departure_time_area'+x.target.value+'"]').value = '';
        feedbackareas.textContent = '';
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
        evt.currentTarget.classList.remove('bg-emerald-500', 'active:bg-emerald-600', 'hover:bg-emerald-700');
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
        evt.currentTarget.classList.add('bg-emerald-500', 'active:bg-emerald-600', 'hover:bg-emerald-700');

        restore();
        inputDocumento.removeEventListener('blur', validarDocumento);
        codigo.removeEventListener('blur', validarCodigo);
        email.removeEventListener('blur', validarEmail);
        telefono.removeEventListener('blur', validarTelefono);

        if('identificacion' in errores){
            delete errores.identificacion;
        }

        if('nombre' in errores){
            delete errores.nombre;
        }

        if('codigo' in errores){
            delete errores.codigo;
        }
        
        if('correo' in errores){
            delete errores.correo;
        }
        
        if('telefono' in errores){
            delete errores.telefono;
        }

        if('sexo' in errores){
            delete errores.sexo;
        }

        if('edad' in errores){
            delete errores.edad;
        }

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
            if('identificacion' in errores){
                delete errores.identificacion;
            }
            feedbackdocumento.textContent = '';

            if(data == true){
                errores.identificacion = 'El documento ya esta registrado';
                feedbackdocumento.textContent = errores.identificacion;
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
            if('codigo' in errores){
                delete errores.codigo;
            }
            feedbackcodigo.textContent = '';

            if(data == true){
                errores.codigo = 'El codigo ya esta registrado';
                feedbackcodigo.textContent = errores.codigo;
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

            let regexEmail = /^[-!#$%&'*+\/0-9=?A-Z^_a-z`{|}~](\.?[-!#$%&'*+\/0-9=?A-Z^_a-z`{|}~])*@[a-zA-Z0-9](-*\.?[a-zA-Z0-9])*\.[a-zA-Z](-?[a-zA-Z0-9])+$/;
            
            if('correo' in errores){
                delete errores.correo;
            }
            feedbackcorreo.textContent = '';

            if(data == true){
                errores.correo = 'El correo ya esta registrado';
                feedbackcorreo.textContent = errores.correo;
            }
            else if(e.target.value.trim().length != 0){
                if(!regexEmail.test(e.target.value)){
                    errores.correo = 'Por favor, proporcione un correo valido';
                    feedbackcorreo.textContent = errores.correo;
                }
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
            if('telefono' in errores){
                delete errores.telefono;
            }
            feedbacktelefono.textContent = '';
            
            if(data == true){
                errores.telefono = 'El telefono ya esta registrado';
                feedbacktelefono.textContent = errores.telefono;
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

        if('identificacion' in errores){
            delete errores.identificacion;
        }

        if('nombre' in errores){
            delete errores.nombre;
        }

        if('codigo' in errores){
            delete errores.codigo;
        }
        
        if('correo' in errores){
            delete errores.correo;
        }
        
        if('telefono' in errores){
            delete errores.telefono;
        }

        if('sexo' in errores){
            delete errores.sexo;
        }

        if('edad' in errores){
            delete errores.edad;
        }

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
    accion.classList.add('bg-emerald-500', 'active:bg-emerald-600', 'hover:bg-emerald-700');

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

function triggerChange(element){
    let changeEvent = new Event('change');
    element.dispatchEvent(changeEvent);
}


//Booking

let bookingTitleDocument,
    bookingDocument;

function changeTypeDocument(evt) {
    bookingTitleDocument = document.querySelector('#titledocument');
    bookingDocument = document.querySelector('#document');
    TIPOS_DOCUMENTOS[evt.value](bookingTitleDocument,bookingDocument);
    bookingDocument.value = '';
}

booking.addEventListener('click',searchBooking);

function searchBooking(e) {

    if(e.target.value == ''){        
        Swal.fire({
            title: 'Datos de la reservación',
            html: `
            <main class="grid justify-items-center gap-5">
                <label for="document-type" class="mt-2">Seleccione el tipo de documento</label>
                <select class="w-3/4 text-xl rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" id="document-type" onchange="changeTypeDocument(this)">
                    <option value="R">RUC</option>
                    <option value="C">Cédula</option>
                    <option value="P">Pasaporte</option>
                </select>
                <label for="document" id="titledocument">Numero de RUC</label>
                <input type="text" id="document" class="w-3/4 text-xl rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 mb-2" placeholder="Ingrese el número de RUC con guiones">
            </main>`,
            showCancelButton: true,
            confirmButtonText: 'Buscar',
            confirmButtonColor: '#10B981',
            cancelButtonColor: '#6B7280',
            showLoaderOnConfirm: true,
            backdrop: true,
            preConfirm: () => {
                const type = Swal.getPopup().querySelector('#document-type').value;
                const document = Swal.getPopup().querySelector('#document').value;
    
                if (!document) {
                    Swal.showValidationMessage(`Por favor, ingrese el número de documento`)
                }
                return fetch('./functions.php',{
                    method: "POST",
                    mode: "same-origin",
                    credentials: "same-origin",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({datos: {
                            solicitud: "book",
                            document_type: type,
                            document: document
                    }})
                })
                .then(response => {
                    if (!response.ok) {
                      throw new Error(response.statusText)
                    }
                    return response.json()
                })
                .catch(error => {
                    Swal.showValidationMessage(`Solicitud fallida: ${error}`)
                })
            },
            allowOutsideClick: () => !Swal.isLoading()
        })
        .then((result) => {
            if (result.isConfirmed) {
                if(result.value.count == 0){
                    Swal.fire({
                        title: 'La reserva no ha sido encontrada!',
                        allowOutsideClick: false,
                        text: 'Por favor, verifica los datos de la reservación',
                        icon: 'error',
                        confirmButtonColor: '#3b82f6'
                    });
                }
                else{
                    e.target.classList.remove('animate-bounce','bg-blue-500','active:bg-blue-600','hover:bg-blue-700');
                    e.target.classList.add('bg-red-500','active:bg-red-600' ,'hover:bg-red-700');
                    e.target.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Anular selección
                    `;
                    e.target.value = result.value.booking['booking_id'];
                    unpackData(result.value);
                    const message = result.value.customer.customer_id
                                    ? 
                                    'El cliente se encuentra registrado, puedes seguir adelante con la visita.' 
                                    : 
                                    'El cliente no se encuentra registrado, debes proceder a registrarlo.';
                    Swal.fire({
                        title: 'La reserva se ha cargado correctamente!',
                        allowOutsideClick: false,
                        text: `${message}`,
                        icon: 'success',
                        confirmButtonColor: '#3b82f6'
                    });
                }
            }
        });
    }
    else{
        location.reload();
    }
}

function unpackData(data) {
    if(data.customer.customer_id){
        tipoDocumento.value = data.customer['document_type'];
        triggerChange(tipoDocumento);

        inputDocumento.value = data.customer['document'];
        triggerKeyup(inputDocumento);

        idHidden.value = data.customer['customer_id'];

        codigo.value = data.customer['code'];
        codigo.disabled = true;
        codigo.classList.add('bg-gray-300');
        codigo.classList.add('cursor-not-allowed');

        nombreUsuario.value = data.customer['name'];
        nombreUsuario.disabled = true;
        nombreUsuario.classList.add('bg-gray-300');
        nombreUsuario.classList.add('cursor-not-allowed');

        if(data.customer['email']){
            email.value = data.customer['email'];
        }
        else{
            email.placeholder = 'Sin correo electrónico asignado';
        }
        email.disabled = true;
        email.classList.add('bg-gray-300');
        email.classList.add('cursor-not-allowed');

        if(data.customer['telephone']){
            telefono.value = data.customer['telephone'];
        }
        else{
            telefono.placeholder = 'Sin teléfono asignado';
        }
        telefono.disabled = true;
        telefono.classList.add('bg-gray-300');
        telefono.classList.add('cursor-not-allowed');

        let edadChecked = Array.from(edad).find( x => x.value == data.customer['range_id']);
        edadChecked.checked = true;
        edad.forEach( e =>{
            e.disabled = true;
            e.classList.remove('text-blue-600')
            e.classList.add('cursor-not-allowed', 'text-gray-500');
        });

        let sexoChecked = Array.from(sexo).find( x => x.value == data.customer['sex']);
        sexoChecked.checked = true;
        sexo.forEach( e =>{
            e.disabled = true;
            e.classList.remove('text-blue-600')
            e.classList.add('cursor-not-allowed', 'text-gray-500');
        });
        provincia.value = data.customer['province_id'];
        provincia.disabled = true;
        provincia.classList.add('bg-gray-300');
        provincia.classList.add('cursor-not-allowed');

        triggerChange(provincia);
        distrito.value = data.customer['district_id'];
        distrito.disabled = true;
        distrito.classList.add('bg-gray-300');
        distrito.classList.add('cursor-not-allowed');
        
        triggerChange(distrito)
        corregimiento.value = data.customer['township_id'];
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
    else{
        tipoDocumento.value = data.customer['document_type'];
        triggerChange(tipoDocumento);

        inputDocumento.value = data.customer['document'];
        triggerKeyup(inputDocumento);

        nombreUsuario.value = data.customer['name'];
        
        containerRegister.classList.remove('hidden');
        accion.innerHTML = '<i class="fas fa-user-times"></i>';
        accion.classList.remove('bg-emerald-500', 'active:bg-emerald-600', 'hover:bg-emerald-700');
        accion.classList.add('bg-red-500', 'active:bg-red-600', 'hover:bg-red-700');

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
    
    razonVisita.value = data.booking['reason_id'];
    triggerChange(razonVisita);

    if(data.areas){
        data.areas.forEach(x => {
            document.querySelector('input[name="'+'areacheck'+x.area_id+'"]').checked = true;
            const areaCheck = document.querySelector('#area'+x.area_id);
            if(areaCheck.nextElementSibling.classList.contains('feed')){
                areaCheck.parentElement.nextElementSibling.classList.remove('mt-5');
            }
            else{
                areaCheck.nextElementSibling.classList.remove('mt-4');
            }
            document.querySelector('#area'+x.area_id).classList.remove('hidden');
            document.querySelector('input[name="arrival_time_area'+x.area_id+'"]').value = x.arrival_time;
            document.querySelector('input[name="departure_time_area'+x.area_id+'"]').value = x.departure_time;
        });
    }
    
    fecha.value = data.booking['date'];
    observacion.value = data.booking['observation'];
}
