//###########Form validation##########
const feeds = document.querySelectorAll('.feed'),
    feedbackdocumento = document.querySelector('#feedbackdocumento'),
    feedbacknombre = document.querySelector('#feedbacknombre'),
    feedbackcorreo = document.querySelector('#feedbackcorreo'),
    feedbacktelefono = document.querySelector('#feedbacktelefono'),
    feedbackedad = document.querySelector('#feedbackedad'),
    feedbackareas = document.querySelector('#feedbackareas'),
    feedbacksexo = document.querySelector('#feedbacksexo');

formulario.addEventListener('submit', guardarEntrada);

function guardarEntrada(evt) {
    evt.preventDefault();
    formulario.removeEventListener('submit', guardarEntrada);

    registrar.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>Procesando...`;

    feeds.forEach(x => {
        x.textContent = '';
    });

    let formData = new FormData();

    let areas = [];

    formData.append('id_razonVisita', razonVisita.value);
    formData.append('solicitud', 'v');
    formData.append('observacion', observacion.value);

    if (booking.value != '') {
        formData.append('booking_id', booking.value);
    }

    if ('areas' in errores) {
        delete errores.areas;
    }

    if (!optionSelected.classList.contains('free') && !optionSelected.classList.contains('group')) {
        areasTrabajo.forEach(evt => {

            if ('area' + evt.value in errores) {
                delete errores['area' + evt.value];
            }
    
            if (evt.checked) {
                let area = {
                    id: evt.value,
                };
                if(checkboxAll.checked){
                    area.arrival_time = document.querySelector('input[name="arrival_time_area-all"]').value,
                    area.departure_time = document.querySelector('input[name="departure_time_area-all"]').value
                }
                else{
                        area.arrival_time= document.querySelector('input[name="arrival_time_area' + evt.value + '"]').value,
                        area.departure_time= document.querySelector('input[name="departure_time_area' + evt.value + '"]').value
                }
    
                if (area.arrival_time.trim().length == 0) {
                    errores['area' + evt.value] = "Por favor, proporcione una hora de llegada";
                    document.querySelector('#feedbackarea' + evt.value).textContent = errores['area' + evt.value];
    
                    document.querySelector('input[name="arrival_time_area' + evt.value + '"]').addEventListener('change', x => {
                        if (x.target.value.trim().length != 0) {
                            document.querySelector('#feedbackarea' + evt.value).textContent = '';
                        }
                    });
                }
    
                areas.push(area);
            }
        });

        if (areas.length == 0) {
            errores.areas = "Por favor, seleccione las areas deseadas";
            feedbackareas.textContent = errores.areas;
        }
        else {
            formData.append('areas', JSON.stringify(areas));
        }

    }


    if (typeVisitValue == 'I') {
        if ('documento' in errores) {
            delete errores.documento;
        }

        if (inputDocumento.value.trim().length == 0) {
            if (tipoDocumento.value == 'R') {
                errores.documento = "Por favor, proporcione un RUC";
            }
            else if (tipoDocumento.value == 'C') {
                errores.documento = "Por favor, proporcione una cédula";
            }
            else {
                errores.documento = "Por favor, proporcione un pasaporte";
            }
            feedbackdocumento.textContent = errores.documento;
        }

        //Se verifica si se está creando un nuevo cliente o no.
        if (accion.children[0].classList.contains('fa-user-times')) {

            if ('id' in errores) {
                delete errores.id;
            }

            if ('nombre' in errores) {
                delete errores.nombre;
            }

            newCustomer = {
                tipo_documento: tipoDocumento.value,
                documento: inputDocumento.value,
                email: email.value.toLowerCase(),
                telefono: telefono.value,
                provincia: provincia.value,
                distrito: distrito.value,
                corregimiento: corregimiento.value
            };

            if (nombreUsuario.value.trim().length == 0) {
                errores.nombre = "Por favor, proporcione un nombre";
                feedbacknombre.textContent = errores.nombre;
                nombreUsuario.addEventListener('change', evt => {
                    feedbacknombre.textContent = '';
                });
            }
            else {
                newCustomer.nombre = nombreUsuario.value;
            }

            const edadChecked = Array.from(edad).find(x => x.checked);
            const sexoChecked = Array.from(sexo).find(x => x.checked);

            if ('edad' in errores) {
                delete errores.edad;
            }

            if (edadChecked) {
                newCustomer.edad = edadChecked.value;
            }
            else {
                errores.edad = "Por favor, seleccione un rango de edad";
                feedbackedad.textContent = errores.edad;
                edad.forEach(x => {
                    x.addEventListener('click', evt => {
                        feedbackedad.textContent = '';
                    });
                })
            }

            if ('sexo' in errores) {
                delete errores.sexo;
            }

            if (sexoChecked) {
                newCustomer.sexo = sexoChecked.value;
            }
            else {
                errores.sexo = "Por favor, seleccione un tipo de sexo";
                feedbacksexo.textContent = errores.sexo;
                sexo.forEach(x => {
                    x.addEventListener('click', evt => {
                        feedbacksexo.textContent = '';
                    });
                })
            }

            formData.append('newCustomer', JSON.stringify(newCustomer));
        }
        else {
            if ('id' in errores) {
                delete errores.id;
            }

            if (idHidden.value.trim().length == 0 && inputDocumento.value.trim().length != 0) {
                errores.id = "Por favor, seleccione o agregue un cliente";
                feedbackdocumento.textContent = errores.id;
            }
            else if (idHidden.value.trim().length != 0 && inputDocumento.value.trim().length != 0) {
                formData.append('id_cliente', idHidden.value);
            }
        }
    }
    else {
        formData.append('file', fileupload.files[0]);
        if (!formData.get("file")) {
            errores.file = "Por favor, seleccione un archivo";
/*             feedbackfile.textContent = errores.file; */
        }
    }

    if (Object.keys(errores).length > 0) {
        formulario.addEventListener('submit', guardarEntrada);
        registrar.innerHTML = `Registrar`;
    }
    else {
        fetch('./functions.php', {
            method: "POST",
            body: formData
        })
        .then(() =>{
            registrar.innerHTML = `Registrado`;
            Swal.fire({
                title: 'La visita se ha guardado!',
                allowOutsideClick: false,
                icon: 'success',
                confirmButtonColor: '#3b82f6',
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        });
    }

}