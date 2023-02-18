//Informacion inicial
let distritos = [];
let corregimientos = [];
let events = [];
let errores = {};

//Provincias-Distritos-Corregimientos
let formDataDistricts = new FormData();
formDataDistricts.append('solicitud', 'd');
fetch('./functions.php', {
    method: "POST",
    body: formDataDistricts
})
    .then(res => res.json())
    .then(data => {
        distritos = data;

        const resul = distritos.filter(x => x.province_id == provincia.value);
        resul.forEach(e => {
            if (e.name == 'Santiago') {
                distrito.innerHTML += `<option value="${e.district_id}" selected>${e.name}</option>`;
            }
            else {
                distrito.innerHTML += `<option value="${e.district_id}" >${e.name}</option>`;
            }
        });

        let formDataTownships = new FormData();
        formDataTownships.append('solicitud', 'c');

        fetch('./functions.php', {
            method: "POST",
            body: formDataTownships
        })
            .then(res => res.json())
            .then(data => {
                corregimientos = data;

                const resul = corregimientos.filter(x => x.district_id == distrito.value);
                resul.forEach(e => {
                    if (e.name == 'Santiago') {
                        corregimiento.innerHTML += `<option value="${e.township_id}" selected>${e.name}</option>`;
                    }
                    else {
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
            $('#autoComplete').val(ui.item.label); // display the selected text
            idHidden.value = ui.item.id;
            
            nombreUsuario.value = ui.item.name;
            nombreUsuario.disabled = true;
            nombreUsuario.classList.add('bg-gray-300');
            nombreUsuario.classList.add('cursor-not-allowed');

            if (ui.item.email) {
                email.value = ui.item.email;
            }
            else {
                email.placeholder = 'Sin correo electrónico asignado';
            }

            email.disabled = true;
            email.classList.add('bg-gray-300');
            email.classList.add('cursor-not-allowed');

            if (ui.item.telephone) {
                telefono.value = ui.item.telephone;
            }
            else {
                telefono.placeholder = 'Sin teléfono asignado';
            }

            telefono.disabled = true;
            telefono.classList.add('bg-gray-300');
            telefono.classList.add('cursor-not-allowed');
            //edad
            let edadChecked = Array.from(edad).find(x => x.value == ui.item.age_range);
            edadChecked.checked = true;
            edad.forEach(e => {
                e.disabled = true;
                e.classList.remove('text-blue-600')
                e.classList.add('cursor-not-allowed', 'text-gray-500');
            })
            //sexo
            let sexoChecked = Array.from(sexo).find(x => x.value == ui.item.sex);
            sexoChecked.checked = true;
            sexo.forEach(e => {
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
                if (e.district_id == ui.item.district) {
                    distrito.innerHTML += `<option value="${e.district_id}" selected>${e.name}</option>`;
                }
                else {
                    distrito.innerHTML += `<option value="${e.district_id}">${e.name}</option>`;
                }
            });

            //corregim
            corregimiento.innerHTML = '';
            items = corregimientos.filter(x => x.district_id == distrito.value);
            items.forEach(e => {
                if (e.township_id == ui.item.township) {
                    corregimiento.innerHTML += `<option value="${e.township_id}">${e.name}</option>`;
                }
                else {
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


//Button action

accion.addEventListener('click', evt => {

    //Se puede aplicar paradigma de objetos...

    if (evt.currentTarget.children[0].classList.contains('fa-user-plus')) {
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
        email.addEventListener('blur', validarEmail);
        telefono.addEventListener('blur', validarTelefono);

        triggerBlur(inputDocumento);
    }
    else if (evt.currentTarget.children[0].classList.contains('fa-user-times')) {
        containerRegister.classList.add('hidden');
        evt.currentTarget.innerHTML = '<i class="fas fa-user-plus"></i>';
        evt.currentTarget.classList.remove('bg-red-500', 'active:bg-red-600', 'hover:bg-red-700');
        evt.currentTarget.classList.add('bg-emerald-500', 'active:bg-emerald-600', 'hover:bg-emerald-700');

        restore();
        inputDocumento.removeEventListener('blur', validarDocumento);
        email.removeEventListener('blur', validarEmail);
        telefono.removeEventListener('blur', validarTelefono);

        if ('identificacion' in errores) {
            delete errores.identificacion;
        }

        if ('nombre' in errores) {
            delete errores.nombre;
        }

        if ('correo' in errores) {
            delete errores.correo;
        }

        if ('telefono' in errores) {
            delete errores.telefono;
        }

        if ('sexo' in errores) {
            delete errores.sexo;
        }

        if ('edad' in errores) {
            delete errores.edad;
        }

        feedbackdocumento.textContent = '';
        feedbackcorreo.textContent = '';
        feedbacktelefono.textContent = '';
    }
    else if (evt.currentTarget.children[0].classList.contains('fa-eye')) {
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
    else if (evt.currentTarget.children[0].classList.contains('fa-eye-slash')) {
        containerRegister.classList.add('hidden');
        evt.currentTarget.innerHTML = '<i class="fas fa-eye"></i>';
        evt.currentTarget.classList.remove('bg-red-500', 'active:bg-red-600', 'hover:bg-red-700');
        evt.currentTarget.classList.add('bg-yellow-500', 'active:bg-yellow-600', 'hover:bg-yellow-700');
    }
});

function validarDocumento(e) {
    let formData = new FormData();
    formData.append('solicitud', 'doc');
    formData.append('documento', e.target.value);
    fetch('./functions.php', {
        method: "POST",
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if ('identificacion' in errores) {
                delete errores.identificacion;
            }
            feedbackdocumento.textContent = '';

            if (data == true) {
                errores.identificacion = 'El documento ya esta registrado';
                feedbackdocumento.textContent = errores.identificacion;
            }
        });
}

function validarEmail(e) {
    let formData = new FormData();
    formData.append('solicitud', 'cor');
    formData.append('email', e.target.value);

    fetch('./functions.php', {
        method: "POST",
        body: formData
    })
        .then(res => res.json())
        .then(data => {

            let regexEmail = /^[-!#$%&'*+\/0-9=?A-Z^_a-z`{|}~](\.?[-!#$%&'*+\/0-9=?A-Z^_a-z`{|}~])*@[a-zA-Z0-9](-*\.?[a-zA-Z0-9])*\.[a-zA-Z](-?[a-zA-Z0-9])+$/;

            if ('correo' in errores) {
                delete errores.correo;
            }
            feedbackcorreo.textContent = '';

            if (data == true) {
                errores.correo = 'El correo ya esta registrado';
                feedbackcorreo.textContent = errores.correo;
            }
            else if (e.target.value.trim().length != 0) {
                if (!regexEmail.test(e.target.value)) {
                    errores.correo = 'Por favor, proporcione un correo valido';
                    feedbackcorreo.textContent = errores.correo;
                }
            }
        });

}

function validarTelefono(e) {
    let formData = new FormData();
    formData.append('solicitud', 'tel');
    formData.append('telefono', e.target.value);

    fetch('./functions.php', {
        method: "POST",
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if ('telefono' in errores) {
                delete errores.telefono;
            }
            feedbacktelefono.textContent = '';

            if (data == true) {
                errores.telefono = 'El telefono ya esta registrado';
                feedbacktelefono.textContent = errores.telefono;
            }
        });

}

inputDocumento.addEventListener('keyup', evt => {

    if (evt.key != "Enter") {
        if (evt.target.value != '') {
            accion.classList.remove('hidden');
            if (!containerRegister.classList.contains('hidden') || accion.classList.contains('bg-yellow-500')) {
                restore();
            }
        }
        else {
            accion.classList.add('hidden');
            restore();
        }
        inputDocumento.removeEventListener('blur', validarDocumento);
        email.removeEventListener('blur', validarEmail);
        telefono.removeEventListener('blur', validarTelefono);

        if ('identificacion' in errores) {
            delete errores.identificacion;
        }

        if ('nombre' in errores) {
            delete errores.nombre;
        }

        if ('correo' in errores) {
            delete errores.correo;
        }

        if ('telefono' in errores) {
            delete errores.telefono;
        }

        if ('sexo' in errores) {
            delete errores.sexo;
        }

        if ('edad' in errores) {
            delete errores.edad;
        }

        feedbackdocumento.textContent = '';
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

    nombreUsuario.disabled = false;
    nombreUsuario.value = '';
    nombreUsuario.classList.remove('bg-gray-300');
    nombreUsuario.classList.remove('cursor-not-allowed');
    email.disabled = false;
    email.value = '';
    email.classList.remove('bg-gray-300');
    email.classList.remove('cursor-not-allowed');
    email.placeholder = 'Ingrese el correo electrónico del cliente';
    telefono.disabled = false;
    telefono.value = '';
    telefono.classList.remove('bg-gray-300');
    telefono.classList.remove('cursor-not-allowed');
    telefono.placeholder = 'Ingrese el número de telefono del cliente';

    //edad
    let resul = Array.from(edad).find(x => x.checked);
    if (resul) {
        resul.checked = false;
    }
    edad.forEach(e => {
        e.disabled = false;
        e.classList.remove('text-gray-500', 'cursor-not-allowed')
        e.classList.add('text-blue-600');
    })


    //sexo
    resul = Array.from(sexo).find(x => x.checked);
    if (resul) {
        resul.checked = false;
    }
    sexo.forEach(e => {
        e.disabled = false;
        e.classList.remove('text-gray-500', 'cursor-not-allowed')
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
    Array.from(provincia.options).forEach((opt) => {
        if (opt.text == 'Veraguas') {
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
        if (e.name == 'Santiago') {
            corregimiento.innerHTML += `<option value="${e.township_id}" selected>${e.name}</option>`;
        }
        else {
            corregimiento.innerHTML += `<option value="${e.township_id}">${e.name}</option>`;
        }
    });

    feeds.forEach(x => {
        x.textContent = '';
    })
}

function triggerBlur(element) {
    let blurEvent = new Event('blur');
    element.dispatchEvent(blurEvent);
}

function triggerClick(element) {
    let clickEvent = new Event('click');
    element.dispatchEvent(clickEvent);
}