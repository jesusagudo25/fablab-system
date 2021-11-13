let btn_generar = document.querySelector('#generar');

btn_generar.addEventListener('click',generarVenta);

function generarVenta(e){
    let errores = {};
    let datos = {}

    btn_generar.removeEventListener('click',generarVenta)

    if(inputDocumento.value.trim().length == 0){
        errores.documento = "Por favor, seleccione un cliente";
    }

    if(idHidden.value.trim().length != 0){
        datos['id_cliente'] = idHidden.value;
    }
    else{
        errores.id = "Por favor, seleccione un cliente";
    }

    if(fecha.value.trim().length == 0){
        errores.fecha = "Por favor, seleccione una fecha";
    }
    else{
        datos["fecha"] = fecha.value
    }

    let filas = document.querySelectorAll("#detalle_venta tr");

    filas.forEach((e,i) => {
        let columnas = e.querySelectorAll("td");

        servicios_ag[i].precio = parseFloat(columnas[2].children[0].value);
    });

    let itemDetalles = [];

    servicios_ag.forEach( value => {
        if(!value.hasOwnProperty('detalles')){
            itemDetalles.push(value.numeroItem)
        }
    })

    if(itemDetalles.length){
        errores.fecha = "Por favor, proporcione los datos del servicio ingresado";
    }

    if(Object.keys(errores).length > 0){
        Swal.fire({
            title: 'Error!',
            text: 'Existen campos incompletos.',
            icon: 'error',
            confirmButtonColor: '#ef4444'
        });

        btn_generar.addEventListener('click',generarVenta);
    }
    else{
        datos["servicios_ag"] = servicios_ag;

        fetch('./savesale.php',{
            method: "POST",
            mode: "same-origin",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({datos: datos})
        })
            .then(res => res.json())
            .then(data => {
                console.log(data)
                Swal.fire({
                    title: 'La venta se ha generado!',
                    allowOutsideClick: false,
                    icon: 'success',
                    confirmButtonColor: '#3b82f6',
                    footer: `<a class="flex items-center justify-between swal2-deny swal2-styled" target="_blank" href="./download.php?venta=${data}" id="pdf">
        </svg>
        <i class="fas fa-file-pdf mr-3"></i>
                  <span>Descargar PDF</span>
                </a>`
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            });
    }

}