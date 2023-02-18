tablaAreas = $('#datatable-json').DataTable({
    language: { url: "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json" },
    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": "./functions.php",
        "data": {
            "solicitud":"vinyls",
        }
    },
    "dom": 'Brtip',
    "initComplete":function( settings, json){
        document.querySelector('.dt-buttons').innerHTML += `<button id="area" class="w-1/2 px-4 py-2 text-sm font-semibold uppercase leading-5 text-center text-white transition-colors duration-150 bg-emerald-500 border border-transparent rounded-lg active:bg-emerald-600 hover:bg-emerald-700 focus:outline-none">Agregar vinilo<i class="fas fa-archive ml-3"></i></button>`;
    },
    "columnDefs": [
        {
            "data": null,
            render:function(data, type, row)
            {
                if(data[6] == 1){
                    return '<button value="'+data[0]+'" type="button" name="desactivar" class="flex items-center justify-between text-2xl px-1 font-medium leading-5 text-emerald-500 rounded-lg focus:outline-none focus:shadow-outline-gray" onclick="interruptor(this)"><i class="fas fa-toggle-on"></i></button>';
                }
                else{
                    return '<button value="'+data[0]+'" type="button" name="activar" class="flex items-center justify-between text-2xl font-medium px-1 leading-5 text-red-500 rounded-lg focus:outline-none focus:shadow-outline-gray" onclick="interruptor(this)"><i class="fas fa-toggle-off"></i></button>';
                }
            },
            "targets": 5
        },
        {
            "data": null,
            render:function(data, type, row)
            {
                return data[3] + '*' + data[4];
            },
            "targets": 3
        },
        {
            "data": null,
            render:function(data, type, row)
            {
                return data[5];
            },
            "targets": 4
        },
        {
            "data": null,
            render:function(data, type, row)
            {
                return '<button value="'+data[0]+'" type="button" class="flex items-center justify-between px-2 py-2 text-lg font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray" onclick="editar(this)"><i class="fas fa-edit"></i></button>';
            },
            "targets": 6
        },
        { 'visible': false, 'targets': [0] }
    ]
});
