let btn_generar = document.querySelector('#generar');

btn_generar.addEventListener('click',generarVenta);

function generarVenta(e){
    let errores = {};
    let datos = {};

    btn_generar.removeEventListener('click',generarVenta);

    if(inputDocumento.value.trim().length == 0){
            errores.documento = "Por favor, proporcione un documento";
        feedbackdocumento.textContent = errores.documento;
    }
    
    if(idHidden.value.trim().length == 0 && inputDocumento.value.trim().length != 0){
        errores.id = "Por favor, seleccione un cliente";
        feedbackdocumento.textContent = errores.id;
    }
    else if(idHidden.value.trim().length != 0 && inputDocumento.value.trim().length != 0){
        datos['id_cliente'] = idHidden.value;
    }

    if(reason == 'S'){
        datos['mano_obra'] = manoObra.value;
        datos['fecha_entrega'] = fecha_entrega.value;
    }
    datos['reason'] = reason;

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

        console.log(datos);

/*         $ajax({
            url: "../../ajax.php",
            type: "POST",
            dataType: "json",
            data: {
                solicitud: "generar_venta",
                sale: datos,
            },
            success: function (response) {
                if(response.status == "success"){
                    Swal.close();
                    Swal.fire({
                        title: 'La venta se ha generado!',
                        allowOutsideClick: false,
                        icon: 'success',
                        confirmButtonColor: '#3b82f6',
                        footer: `<a class="flex items-center justify-between swal2-deny swal2-styled" target="_blank" href="./download.php?factura=${data}" id="pdf">
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
            }
        }); */
    }
}