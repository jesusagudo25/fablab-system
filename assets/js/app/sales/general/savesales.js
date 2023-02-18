let btn_generar = document.querySelector('#generar');

btn_generar.addEventListener('click',generarVenta);

function generarVenta(e){
    let errores = {};
    let datos = {}

    btn_generar.removeEventListener('click',generarVenta);

    //Validación de usuario

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

        datos['newCustomer'] = newCustomer;
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
            datos['id_cliente'] = idHidden.value;
        }
    }

    //----------------------VALIDACIONES DE LOS PRODUCTOS----------------------//

    let filas = document.querySelectorAll("#detalle_venta tr");

    filas.forEach((e,i) => {
        let columnas = e.querySelectorAll("td");
        if(Number.isNaN(parseFloat(columnas[2].children[0].value))){
            columnas[2].children[0].classList.remove('border-gray-300','focus:border-blue-300','focus:ring-blue-200');
            columnas[2].children[0].classList.add('border-red-300','focus:border-red-300','focus:ring-red-200');
        }
        else{
            servicios_ag[i].precio = parseFloat(columnas[2].children[0].value);
        }

    });

    let itemDetalles = [];

    servicios_ag.forEach( value => {

        if(!value.hasOwnProperty('detalles')){
            itemDetalles.push(value.numeroItem);
            const btnDetails = document.querySelector('#'+value.numeroItem+' '+'.btn-detalles');
            btnDetails.classList.remove('bg-blue-500','active:bg-blue-600','hover:bg-blue-700');
            btnDetails.classList.add('bg-red-500','active:bg-red-600','hover:bg-red-700');
        }
    });

    if(itemDetalles.length){
        errores.itemDetalles = "Por favor, proporcione los datos del servicio ingresado";
    }

    if(Object.keys(errores).length > 0){

        btn_generar.addEventListener('click',generarVenta);
    }
    else{
        datos["servicios_ag"] = servicios_ag;
        datos.total = total;

        Swal.fire({
            title: 'Cargando...',
            html: 'Espere por favor...',
            allowEscapeKey: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()
            }
        });

        $.ajax({
            url: "./functions.php",
            type: "POST",
            dataType: "json",
            data: {
                solicitud: "generar_venta",
                sale: datos,
            },
            success: function (response) {
                Swal.close();
                Swal.fire({
                    title: 'La venta se ha generado!',
                    allowOutsideClick: false,
                    icon: 'success',
                    confirmButtonColor: '#3b82f6',
                    footer: `<a class="flex items-center justify-between swal2-deny swal2-styled" target="_blank" href="../download.php?factura=${response}" id="pdf">
        </svg>
        <i class="fas fa-file-pdf mr-3"></i>
                <span>Descargar PDF</span>
                </a>`
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            }
        });
    }
}