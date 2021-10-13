//###########Form input##########

const tipoDocumento = document.querySelector('select[name="tipodocumento"]'),
     tituloDocumento=  document.querySelector('#tituloDocumento'),
    inputDocumento = document.querySelector('input[name="documento"]'),
    nombreUsuario = document.querySelector('input[name="name"]'),
    areasTrabajo = document.querySelectorAll('input[type="checkbox"]'),
    razonVisita = document.querySelector('select[name="razonvisita"]'),
    containerArea = document.querySelector('#containerarea')
    idHidden = document.querySelector('input[type="hidden"]')
    accion = document.querySelector('#action'),
    containerRegister = document.querySelector('#containerregister'),
        registrar = document.querySelector('input[type="submit"]'),
    observacion = document.querySelector('textarea[name="observation"]')
    fecha = document.querySelector('input[name="fecha"]'),
    feedbackdocumento = document.querySelector('#feedbackdocumento');

//Tipo de documento -> RUC/CEDULA/PASAPORTE

const TIPOS_DOCUMENTOS = {
    C: () => {
        tituloDocumento.textContent = 'Cédula';
        inputDocumento.placeholder = "9-725-2312";
        nombreUsuario.placeholder = "Carla Batista";
        feedbackdocumento.textContent = 'Por favor, proporcione una cédula';
    },
    R: () => {
        tituloDocumento.textContent = 'RUC';
        inputDocumento.placeholder = "30394-0002-238626";
        nombreUsuario.placeholder = "Yoytec SA";
        feedbackdocumento.textContent = 'Por favor, proporcione un RUC';
    },
    P: () => {
        tituloDocumento.textContent = 'Pasaporte';
        inputDocumento.placeholder = "O300004";
        nombreUsuario.placeholder = "Carla Batista";
        feedbackdocumento.textContent = 'Por favor, proporcione un pasaporte'
    }
}

tipoDocumento.addEventListener('change', evt => {
    TIPOS_DOCUMENTOS[evt.target.value]();
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
            Toastify({
                text: "Visitante seleccionado",
                duration: 3000,
                backgroundColor: "linear-gradient(to right, #00b09b, #059669)",
            }).showToast();
            return false;
        }

    });


});


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

//Button action

accion.addEventListener('click',evt => {
    if(evt.currentTarget.classList.contains('bg-green-600')){
        containerRegister.classList.remove('hidden');
        evt.currentTarget.innerHTML = '<i class="fas fa-user-times"></i>';
        evt.currentTarget.classList.remove('bg-green-600', 'active:bg-green-600', 'hover:bg-green-700');
        evt.currentTarget.classList.add('bg-red-600', 'active:bg-red-600', 'hover:bg-red-700');

        Toastify({
            text: "Se registrará un nuevo usuario",
            duration: 3000,
            backgroundColor: "linear-gradient(to right, #00b09b, #059669)",
        }).showToast();
    }
    else{
        containerRegister.classList.add('hidden');
        evt.currentTarget.innerHTML = '<i class="fas fa-user-plus"></i>';
        evt.currentTarget.classList.remove('bg-red-600', 'active:bg-red-600', 'hover:bg-red-700');
        evt.currentTarget.classList.add('bg-green-600', 'active:bg-green-600', 'hover:bg-green-700');
    }
});

//###########Form validation##########
registrar.addEventListener('click',evt => {
    evt.preventDefault();
    let areas = [];
    let errores = {};
    let datos = {
        id_razonvisita: razonVisita.value,
        fecha: fecha.value,
    };

    if(optionSelected.classList.contains('notfree')){
        areasTrabajo.forEach(evt =>{
            if(evt.checked){
                let area = {
                    id: evt.value,
                    arrival_time: document.querySelector('#arrival_time_area'+evt.value).value,
                    departure_time: document.querySelector('#departure_time_area'+evt.value).value
                }

                if(area.arrival_time.trim().length == 0){
                    errores.areallegada = "Por favor, proporcione una hora de llegada";
                }
                areas.push(area);
            }
        });

        if(areas.length == 0){
            errores.areas = "Por favor, seleccione las areas deseadas";
        }

        datos["areasChecked"] = areas;
    }

    if(observacion.value.trim().length != 0){
        datos["observacion"] = observacion.value;
    }

    if(fecha.value.trim().length == 0){
        errores.fecha = "Por favor, seleccione una fecha";
    }

    if(inputDocumento.value.trim().length == 0){
        errores.documento = feedbackdocumento.value;
    }

    if(containerRegister.classList.contains('hidden')){
        if(idHidden.value.trim().length != 0){
            datos['id_cliente'] = idHidden.value;
        }
        else{
            errores.id = "Por favor, seleccione un cliente";
        }
    }
    else{
        const  codigo = document.querySelector('input[name="codigo"]').value,
            email = document.querySelector('input[name="email"]').value,
            telefono = document.querySelector('input[name="telefono"]').value,
            provincia = document.querySelector('select[name="provincia"]').value,
            ciudad = document.querySelector('input[name="ciudad"]').value,
            corregimiento = document.querySelector('input[name="corregimiento"]').value;

        if(nombreUsuario.value.trim().length == 0){
            errores.correo = "Por favor, proporcione un nombre";
        }

        if(email.trim().length == 0){
            errores.correo = "Por favor, proporcione un correo";
        }

        if(telefono.trim().length == 0){
            errores.telefono = "Por favor, proporcione un telefono";
        }

        if(provincia.trim().length == 0){
            errores.provincia = "Por favor, proporcione una provincia";
        }

        if(ciudad.length == 0){
            errores.ciudad = "Por favor, proporcione una ciudad";
        }

        if(corregimiento.length == 0){
            errores.corregimiento = "Por favor, proporcione un corregimiento";
        }

        datos['newCustomer'] = {
            tipo_documento: tipoDocumento.value,
            documento: inputDocumento.value,
            codigo: codigo,
            nombre: nombreUsuario.value,
            email: email,
            telefono: telefono,
            provincia: provincia,
            ciudad: ciudad,
            corregimiento: corregimiento
        }
    }

    if(Object.keys(errores).length > 0){
        Swal.fire({
            title: 'Error!',
            text: 'Existen campos incompletos.',
            icon: 'error',
            confirmButtonColor: '#d95252'
        });
    }
    else{
        fetch('./saveform.php',{
            method: "POST",
            mode: "same-origin",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({datos: datos})
        })
            .then(data =>{
                Swal.fire({
                    title: 'La visita se ha guardado!',
                    allowOutsideClick: false,
                    icon: 'success',
                    confirmButtonColor: '#3085d6'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            });
    }

});