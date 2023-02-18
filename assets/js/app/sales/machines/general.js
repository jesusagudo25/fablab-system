const tipoDocumento = document.querySelector('select[name="tipodocumento"]'),
    tituloDocumento = document.querySelector('#tituloDocumento'),
    inputDocumento = document.querySelector('input[name="documento"]'),
    nombreUsuario = document.querySelector('input[name="name"]'),
    idHidden = document.querySelector('input[type="hidden"]'),
    accion = document.querySelector('#action'),
    agregar = document.querySelector('#agregar'),
    cotizacion = document.querySelector('#cotizacion'),
    categoria_servicio = document.querySelector('select[name="categoria_servicio"]'),
    servicio = document.querySelector('select[name="servicio"]'),
    nombreCliente = document.querySelector('input[name="name"]'),
    closeModal = document.querySelectorAll('.close'),
    modal = document.querySelector('#modal'),
    guardar = document.querySelector('button[name="guardar"]'),
    modal_content = document.querySelector('#modal-content'),
    feeds = document.querySelectorAll('.feed'),
    visitType = document.querySelectorAll('#container-typevisit input[type="radio"]'),
    manoObra = document.querySelector('input[name="mano_obra"]'),
    fecha_entrega = document.querySelector('input[name="fecha_entrega"]'),
    formulario = document.querySelector('form');

let reason = document.querySelector('#container-typevisit input[type="radio"]:checked').value;
if(reason == 'M'){
    cotizacion.classList.add('hidden');
    manoObra.parentElement.classList.add('invisible');
    fecha_entrega.parentElement.classList.add('invisible');
}
else{
    manoObra.parentElement.classList.remove('invisible');
    fecha_entrega.parentElement.classList.remove('invisible');
    cotizacion.classList.remove('hidden');
}

manoObra.addEventListener('change', e => {
    if(e.target.value != ''){
        calcular();
    }
})


visitType.forEach(e => {
    e.addEventListener('change', () => {
        reason = e.value;
        
        if(reason == 'M'){
            manoObra.parentElement.classList.toggle('invisible');
            fecha_entrega.parentElement.classList.toggle('invisible');
            cotizacion.classList.toggle('hidden');
        }
        else{
            manoObra.parentElement.classList.toggle('invisible');
            fecha_entrega.parentElement.classList.toggle('invisible');
            cotizacion.classList.toggle('hidden');
        }
    });
}
);

//Obtener las areas
let servicios = [];

let formDataServicios = new FormData();
formDataServicios.append('solicitud', 's');
fetch('./functions.php', {
    method: 'POST',
    body: formDataServicios
}).then(res => res.json()).then(data => {
    servicios = data;
}).catch(err => console.log(err));

tipoDocumento.addEventListener('change', evt => {
    inputDocumento.value = '';
    triggerKeyup(inputDocumento)
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
            $('#autoComplete').val(ui.item.label);
            idHidden.value = ui.item.id;
            nombreCliente.value = ui.item.name;

            Toastify({
                text: "Visitante seleccionado",
                duration: 3000,
                style: {
                    background: '#10B981'
                }
            }).showToast();
        }
    }

});

function triggerKeyup(element) {
    let changeEvent = new Event('keyup');
    element.dispatchEvent(changeEvent);
}

inputDocumento.addEventListener('keyup', evt => {

    if (evt.key != "Enter") {
        idHidden.value = '';
        nombreCliente.value = '';
        feedbackdocumento.textContent = '';
    }

});
