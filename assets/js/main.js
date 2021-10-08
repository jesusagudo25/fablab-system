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
        registrar = document.querySelector('input[type="submit"]')
    observacion = document.querySelector('textarea[name="observation"]');

//Tipo de documento -> RUC/CEDULA/PASAPORTE

const TIPOS_DOCUMENTOS = {
    C: () => {
        tituloDocumento.textContent = 'Cédula';
        inputDocumento.placeholder = "9-725-2312";
        nombreUsuario.placeholder = "Carla Batista";
    },
    R: () => {
        tituloDocumento.textContent = 'RUC';
        inputDocumento.placeholder = "30394-0002-238626";
        nombreUsuario.placeholder = "Avicola Grecia";
    },
    P: () => {
        tituloDocumento.textContent = 'Pasaporte';
        inputDocumento.placeholder = "O300004";
        nombreUsuario.placeholder = "Carla Batista";
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
                url: "ajax.php",
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
            if(item.target.checked){
                item.target.parentNode.nextElementSibling.classList.remove('hidden');
                if(item.target.parentNode.nextElementSibling.nextElementSibling){item.target.parentNode.nextElementSibling.nextElementSibling.classList.remove('mt-4');}
            }
            else{
                item.target.parentNode.nextElementSibling.classList.add('hidden');
                if(item.target.parentNode.nextElementSibling.nextElementSibling){item.target.parentNode.nextElementSibling.nextElementSibling.classList.add('mt-4');}
            }
    });
});

//Button action

accion.addEventListener('click',evt => {
    if(evt.currentTarget.classList.contains('bg-green-600')){
        containerRegister.classList.remove('hidden');
        evt.currentTarget.innerHTML = '<i class="fas fa-user-times"></i>';
        evt.currentTarget.classList.remove('bg-green-600', 'active:bg-green-600', 'hover:bg-green-700');
        evt.currentTarget.classList.add('bg-red-600', 'active:bg-red-600', 'hover:bg-red-700');
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
    let valorTipoDocumento = inputDocumento.value;
    let valorDocumento = inputDocumento.value;
    let valorIdHidden = idHidden.value;
    let valorRazonVisita = razonVisita.value;
    let arrival_time = [];
    let departure_time = [];
    let areasChecked = [];
    let valorObservacion = observacion.value;

    if(!optionSelected.classList.contains('free')){
        areasTrabajo.forEach(evt =>{
            if(evt.checked){
                areasChecked.push(evt.value);
                arrival_time.push(document.querySelector('#arrival_time_area'+evt.value).value);
                departure_time.push(document.querySelector('#departure_time_area'+evt.value).value);
            }
        });

        if(areasChecked.length == 0){
            containerArea.lastElementChild.classList.remove('hidden');
        }

    }

    if(valorIdHidden.trim().length == 0 || valorDocumento.trim().length == 0){
        inputDocumento.classList.remove('border-gray-300','focus:border-blue-300','focus:ring-blue-200');
        inputDocumento.classList.add('border-red-600','focus:border-red-400','focus:ring-red-200');
        inputDocumento.parentNode.nextElementSibling.classList.remove('hidden');
    }



    if(containerRegister.classList.contains('hidden')){

        fetch('./saveform.php',{
            method: "POST",
            mode: "same-origin",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({datos: {
                id_cliente: valorIdHidden,
                    tipo_documento: valorTipoDocumento,
                    id_razonvisita: valorRazonVisita,
                    areaschecked: areasChecked,
                    arrival_time: arrival_time,
                    departure_time: departure_time,
                    observacion: valorObservacion

                }})
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
    else{
        const email = document.querySelector('input[name="email"]'),
            provincia = document.querySelector('select[name="provincia"]'),
            ciudad = document.querySelector('input[name="ciudad"]'),
            corregimiento = document.querySelector('input[name="corregimiento"]');
    }


});