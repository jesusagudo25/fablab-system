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

    if(accion.children[0].classList.contains('fa-user-times')){

        if(nombreUsuario.value.trim().length == 0){
            errores.correo = "Por favor, proporcione un nombre";
        }

        if(email.value.trim().length == 0){
            errores.correo = "Por favor, proporcione un correo";
        }

        if(telefono.value.trim().length == 0){
            errores.telefono = "Por favor, proporcione un telefono";
        }

        if(provincia.value.trim().length == 0){
            errores.provincia = "Por favor, proporcione una provincia";
        }

        if(distrito.value.length == 0){
            errores.distrito = "Por favor, proporcione una distrito";
        }

        if(corregimiento.value.length == 0){
            errores.corregimiento = "Por favor, proporcione un corregimiento";
        }

        console.log(provincia.value,distrito.value,corregimiento.value);

        datos['newCustomer'] = {
            tipo_documento: tipoDocumento.value,
            documento: inputDocumento.value,
            codigo: codigo.value,
            nombre: nombreUsuario.value,
            email: email.value,
            telefono: telefono.value,
            provincia: provincia.value,
            distrito: distrito.value,
            corregimiento: corregimiento.value
        }

    }
    else{
        if(idHidden.value.trim().length != 0){
            datos['id_cliente'] = idHidden.value;

        }
        else{
            errores.id = "Por favor, seleccione un cliente";
        }
    }

    if(Object.keys(errores).length > 0){
        Swal.fire({
            title: 'Error!',
            text: 'Existen campos incompletos.',
            icon: 'error',
            confirmButtonColor: '#ef4444'
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
                    confirmButtonColor: '#3b82f6'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            });
    }

});