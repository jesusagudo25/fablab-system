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
    ajax:{
        url: './functions.php',
        type: 'POST',
        data: {solicitud:'c'},
        dataSrc:""
    },
    dom: 'Bfrtip',
    buttons: [
        {
            extend: 'colvis',
            columns: ':not(".select-disabled")'
        }
    ],
    columns: [
        { "data": "customer_id" },
        { "data": "document" },
        {
            "data": null,
            render:function(data, type, row)
            {

                if(data['code']){
                    return data['code'];
                }
                else{
                    return 'Sin codigo';
                }
            },
            "targets": -1
        },
        { "data": "customer_name" },
        { "data": "range_name" },
        { "data": "sex" },
        {
            "data": null,
            render:function(data, type, row)
            {

                if(data['email']){
                    return data['email'];
                }
                else{
                    return 'Sin correo';
                }
            },
            "targets": -1
        },
        {
            "data": null,
            render:function(data, type, row)
            {
                if(data['telephone']){
                    return data['telephone'];
                }
                else{
                    return 'Sin telefono';
                }
            },
            "targets": -1
        },
        { "data": "province_name" },
        { "data": "district_name" },
        { "data": "township_name" },
        {
            "data": null,
            render:function(data, type, row)
            {
                if(data['status']){
                    return '<button value="'+data['customer_id']+'" type="button" name="desactivar" class="flex items-center justify-between text-2xl px-1 font-medium leading-5 text-green-500 rounded-lg focus:outline-none focus:shadow-outline-gray .btn-borrar" onclick="interruptor(this)"><i class="fas fa-toggle-on"></i></button>';
                }
                else{
                    return '<button value="'+data['customer_id']+'" type="button" name="activar" class="flex items-center justify-between text-2xl font-medium px-1 leading-5 text-red-500 rounded-lg focus:outline-none focus:shadow-outline-gray .btn-borrar" onclick="interruptor(this)"><i class="fas fa-toggle-off"></i></button>';
                }
            },
            "targets": -1
        },
        {
            "data": null,
            render:function(data, type, row)
            {
                return '<button value="'+data['customer_id']+'" type="button" class="flex items-center justify-between px-2 py-2 text-lg font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray btn-editar" onclick="editar(this)"><i class="fas fa-edit"></i></i></button>';
            },
            "targets": -1
        }

    ],
    responsive: true,
    processing: true,
    'columnDefs' : [
        //hide the second & fourth column
        { 'visible': false, 'targets': [0,4,5,7,8,9,10] }
    ],
    order: [[ 0, "desc" ]]
});


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
    corregimiento = document.querySelector('select[name="corregimiento"]');


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

function triggerChange(element){
    let changeEvent = new Event('change');
    element.dispatchEvent(changeEvent);
}

function editar(e){
    $.ajax({
        url: "./functions.php",
        type: "POST",
        datatype:"json",
        data:  {
            solicitud: "id_c",
            id: e.value,
        },
        success: function(data) {

            let tipoDocumentoSelect = Array.from(tipoDocumento.options).find( opt => opt.value == data['document_type']);
            tipoDocumentoSelect.selected = true;
            
            inputDocumento.value = data['document'];
            codigo.value = data['code'];
            nombreUsuario.value = data['name'];
            
            let edadCheck = Array.from(edad).find( opt => opt.value == data['range_id']);
            edadCheck.checked = true;

            let sexoCheck = Array.from(sexo).find( opt => opt.value == data['sex']);
            sexoCheck.checked = true;
            
            email.value = data['email'];
            telefono.value = data['telephone'];

            let provinciaSelect = Array.from(provincia.options).find( opt => opt.value == data['province_id']);
            provinciaSelect.selected = true;

            triggerChange(provincia);

            let districtSelect = Array.from(distrito.options).find( opt => opt.value == data['district_id']);
            districtSelect.selected = true;

            let townshipSelect = Array.from(corregimiento.options).find( opt => opt.value == data['township_id']);
            townshipSelect.selected = true;

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
            }

            function actualizar(evt) {
                edadCheck = Array.from(edad).find( opt => opt.checked);
                sexoCheck = Array.from(sexo).find( opt => opt.checked);

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
    });
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