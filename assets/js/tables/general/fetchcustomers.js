//Informacion inicial
let distritos = [];
let corregimientos = [];

$.ajax({
    url: "./functions.php",
    type: "POST",
    datatype:"json",
    data:  {
        solicitud: "dis",
    },
    success: function(data) {
        distritos = data;

        $.ajax({
            url: "./functions.php",
            type: "POST",
            datatype:"json",
            data:  {
                solicitud: "cor",
            },
            success: function(data) {
                corregimientos = data;
            }
        });
    }
});

tablaClientes = $('#datatable-json').DataTable({
    language: { url: "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json" },
    "processing": true,
    "serverSide": true,
    "ajax": "./functions.php",
    "columnDefs": 
    [
        {
            "data": null,
            render:function(data, type, row)
            {

                if(data[2]){
                    return data[2];
                }
                else{
                    return 'Sin codigo';
                }
            },
            "targets": 2
        },
        {
            "data": null,
            render:function(data, type, row)
            {

                if(data[4]){
                    return data[4];
                }
                else{
                    return 'Sin correo';
                }
            },
            "targets": 4
        },
        {
            "data": null,
            render:function(data, type, row)
            {
                if(data[5]){
                    return '<button value="'+data[0]+'" type="button" name="desactivar" class="flex items-center justify-between text-2xl px-1 font-medium leading-5 text-emerald-500 rounded-lg focus:outline-none focus:shadow-outline-gray .btn-borrar" onclick="interruptor(this)"><i class="fas fa-toggle-on"></i></button>';
                }
                else{
                    return '<button value="'+data[0]+'" type="button" name="activar" class="flex items-center justify-between text-2xl font-medium px-1 leading-5 text-red-500 rounded-lg focus:outline-none focus:shadow-outline-gray .btn-borrar" onclick="interruptor(this)"><i class="fas fa-toggle-off"></i></button>';
                }
            },
            "targets": 5
        },
        {
            "data": null,
            render:function(data, type, row)
            {
                return '<button value="'+data[0]+'" type="button" class="flex items-center justify-between px-2 py-2 text-lg font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray btn-editar" onclick="editar(this)"><i class="fas fa-edit"></i></i></button>';
            },
            "targets": 6
        },
        { "visible": false,  "targets": [ 0 ] }
    ],
    order: [[ 0, "desc" ]]
});


let errores = {};
const closeModal = document.querySelectorAll('.close'),
    modal = document.querySelector('#modal'),
    guardar = document.querySelector('button[name="guardar"]'),
    tipoDocumento = document.querySelector('select[name="tipodocumento"]'),
    tituloDocumento=  document.querySelector('#tituloDocumento'),
    inputDocumento = document.querySelector('input[name="documento"]'),
    nombreUsuario = document.querySelector('input[name="name"]'),
    codigo = document.querySelector('input[name="codigo"]'),
    email = document.querySelector('input[name="email"]'),
    telefono = document.querySelector('input[name="telefono"]'),
    edad = document.querySelectorAll('input[name="edad"]'),
    sexo = document.querySelectorAll('input[name="sexo"]'),
    provincia = document.querySelector('select[name="provincia"]'),
    distrito = document.querySelector('select[name="distrito"]'),
    corregimiento = document.querySelector('select[name="corregimiento"]'),
    feeds = document.querySelectorAll('.feed');


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

//Tipo de documento -> RUC/CEDULA/PASAPORTE
const TIPOS_DOCUMENTOS = {
    C: () => {
        tituloDocumento.textContent = 'Número de cédula';
        inputDocumento.placeholder = "Ingrese el número de cédula con guiones";
    },
    R: () => {
        tituloDocumento.textContent = 'Número de RUC';
        inputDocumento.placeholder = "Ingrese el número de RUC con guiones";
    },
    P: () => {
        tituloDocumento.textContent = 'Número de Pasaporte';
        inputDocumento.placeholder = "Ingrese el número de pasaporte con guiones";
    }
}

tipoDocumento.addEventListener('change', evt => {
    TIPOS_DOCUMENTOS[evt.target.value]();
    inputDocumento.value = '';
    feedbackdocumento.textContent = '';
});

function triggerChange(element){
    let changeEvent = new Event('change');
    element.dispatchEvent(changeEvent);
}

let getDocumento;
let getCodigo;
let getCorreo;
let getTelefono;

function editar(e){
    
    feeds.forEach(x =>{
        x.textContent = '';
    });

    $.ajax({
        url: "./functions.php",
        type: "POST",
        datatype:"json",
        data:  {
            solicitud: "id_c",
            id: e.value,
        },
        success: function(data) {

            inputDocumento.addEventListener('change', validarDocumento);
            codigo.addEventListener('change', validarCodigo);
            email.addEventListener('change', validarEmail);
            telefono.addEventListener('change', validarTelefono);

            getDocumento = data['document'];
            getCodigo = data['code'];
            getCorreo = data['email'];
            getTelefono = data['telephone'];

            tipoDocumento.value = data['document_type'];

            triggerChange(tipoDocumento);
            
            inputDocumento.value = data['document'];
            codigo.value = data['code'];
            nombreUsuario.value = data['name'];
            
            let edadCheck = Array.from(edad).find( opt => opt.value == data['range_id']);
            edadCheck.checked = true;

            let sexoCheck = Array.from(sexo).find( opt => opt.value == data['sex']);
            sexoCheck.checked = true;
            
            email.value = data['email'];
            telefono.value = data['telephone'];
        
            provincia.value = data['province_id'];

            triggerChange(provincia);

            distrito.value = data['district_id'];

            triggerChange(distrito)

            corregimiento.value = data['township_id'];

            modal.classList.toggle('hidden');

            guardar.addEventListener('click', actualizar);
            closeModal.forEach( e =>{
                e.addEventListener('click', cerrarActualizar);
            });

            function cerrarActualizar(e){
                modal.classList.toggle('hidden');
                guardar.removeEventListener('click', actualizar);
                closeModal.forEach( e =>{
                    e.removeEventListener('click', cerrarActualizar);
                });
                inputDocumento.removeEventListener('change', validarDocumento);
                codigo.removeEventListener('change', validarCodigo);
                email.removeEventListener('change', validarEmail);
                telefono.removeEventListener('change', validarTelefono);
            }

            function actualizar(evt) {

                if('documento' in errores){
                    delete errores.documento;
                }

                if(inputDocumento.value.trim().length == 0){
                    if(tipoDocumento.value == 'R'){
                        errores.documento = "Por favor, proporcione un RUC";
                        feedbackdocumento.textContent = errores.documento;
                    }
                    else if(tipoDocumento.value == 'C'){
                        errores.documento = "Por favor, proporcione una cédula";
                        feedbackdocumento.textContent = errores.documento;
                    }
                    else{
                        errores.documento = "Por favor, proporcione un pasaporte";
                        feedbackdocumento.textContent = errores.documento;
                    }
                }

                if('nombre' in errores){
                    delete errores.nombre;
                }

                if(nombreUsuario.value.trim().length == 0){
                    errores.nombre = "Por favor, proporcione un nombre";
                    feedbacknombre.textContent = errores.nombre;
                    nombreUsuario.addEventListener('change', evt =>{
                        feedbacknombre.textContent = '';
                    });
                }

                edadCheck = Array.from(edad).find( opt => opt.checked);
                sexoCheck = Array.from(sexo).find( opt => opt.checked);

                if('edad' in errores){
                    delete errores.edad;
                }
        
                if(!edadCheck){
                    errores.edad = "Por favor, seleccione un rango de edad";
                    feedbackedad.textContent = errores.edad;
                    edad.forEach(x =>{
                        x.addEventListener('click', evt =>{
                            feedbackedad.textContent = '';
                        });
                    })
                }
        
                if('sexo' in errores){
                    delete errores.sexo;
                }
        
                if(!sexoCheck){
                    errores.sexo = "Por favor, seleccione un tipo de sexo";
                    feedbacksexo.textContent = errores.sexo;
                    sexo.forEach(x =>{
                        x.addEventListener('click', evt =>{
                            feedbacksexo.textContent = '';
                        });
                    })
                }

                if(Object.keys(errores).length == 0){                
                    $.ajax({
                        url: "./functions.php",
                        type: "POST",
                        datatype:"json",
                        data:  {
                            solicitud: "u",
                            document_type: tipoDocumento.value,
                            document: inputDocumento.value,
                            code: codigo.value,
                            name: nombreUsuario.value,
                            email: email.value,
                            telephone: telefono.value,
                            age_range: edadCheck.value,
                            sexo: sexoCheck.value,
                            province_id: provincia.value,
                            district_id: distrito.value,
                            township_id: corregimiento.value,
                            id: e.value
                        },
                        success: function(data) {
                            tablaClientes.ajax.reload();
                            guardar.removeEventListener('click', actualizar);
                            closeModal.forEach( e =>{
                                e.removeEventListener('click', cerrarActualizar);
                            });
                            inputDocumento.removeEventListener('change', validarDocumento);
                            codigo.removeEventListener('change', validarCodigo);
                            email.removeEventListener('change', validarEmail);
                            telefono.removeEventListener('change', validarTelefono);
                            Toastify({
                                text: "Cliente actualizado",
                                duration: 3000,
                                style: {
                                    background: '#10B981'
                                }
                            }).showToast();
                            modal.classList.toggle('hidden');
                        }
                    });
                }
            }
        }
    });
}

function validarDocumento(e){

    if('identificacion' in errores){
        delete errores.identificacion;
    }
    feedbackdocumento.textContent = '';

    if(e.target.value != getDocumento){
        $.ajax({
            url: "./functions.php",
            type: "POST",
            datatype:"json",
            data:  {
                solicitud: "doc",
                document: e.target.value,
            },
            success: function(data) {    
                if(data == true){
                    errores.identificacion = 'El documento ya esta registrado';
                    feedbackdocumento.textContent = errores.identificacion;
                }
            }
        });
    }
}

function validarCodigo(e){

    if('codigo' in errores){
        delete errores.codigo;
    }
    feedbackcodigo.textContent = '';

    if(e.target.value != getCodigo){
        $.ajax({
            url: "./functions.php",
            type: "POST",
            datatype:"json",
            data:  {
                solicitud: "cod",
                code: e.target.value,
            },
            success: function(data) {
                if(data == true){
                    errores.codigo = 'El codigo ya esta registrado';
                    feedbackcodigo.textContent = errores.codigo;
                }
            }
        });
    }
}

function validarEmail(e){

    if('correo' in errores){
        delete errores.correo;
    }
    feedbackcorreo.textContent = '';

    if(e.target.value != getCorreo){    
        $.ajax({
            url: "./functions.php",
            type: "POST",
            datatype:"json",
            data:  {
                solicitud: "ema",
                email: e.target.value,
            },
            success: function(data) {
                let regexEmail = /^[-!#$%&'*+\/0-9=?A-Z^_a-z`{|}~](\.?[-!#$%&'*+\/0-9=?A-Z^_a-z`{|}~])*@[a-zA-Z0-9](-*\.?[a-zA-Z0-9])*\.[a-zA-Z](-?[a-zA-Z0-9])+$/;
    
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
            }
        });
    }
}

function validarTelefono(e){

    if('telefono' in errores){
        delete errores.telefono;
    }
    feedbacktelefono.textContent = '';

    if(e.target.value != getTelefono){      
        $.ajax({
            url: "./functions.php",
            type: "POST",
            datatype:"json",
            data:  {
                solicitud: 'tel',
                telephone: e.target.value,
            },
            success: function(data) {                
                if(data == true){
                    errores.telefono = 'El telefono ya esta registrado';
                    feedbacktelefono.textContent = errores.telefono;
                }
            }
        });
    }
}

function interruptor(e) {
    
    let estado = 0;

    if(e.name == "activar"){
        estado = 1;
    }

    $.ajax({
        url: "./functions.php",
        type: "POST",
        datatype:"json",
        data:  {
            solicitud: "d",
            id: e.value,
            status: estado
        },
        success: function(data) {
            tablaClientes.ajax.reload();
            if(estado){
                Toastify({
                    text: "Cliente activado!",
                    duration: 3000,
                    style: {
                        background: '#10B981'
                    }
                }).showToast();
            }
            else{
                Toastify({
                    text: "Cliente desactivado!",
                    duration: 3000,
                    style: {
                        background: '#EF4444'
                    }
                }).showToast();
            }
        }
    });

}