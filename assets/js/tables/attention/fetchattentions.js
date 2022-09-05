tablaVisitas = $('#datatable-json').DataTable({
    language: { url: "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json" },
    "processing": true,
    "serverSide": true,
    "ajax": "./functions.php",
    "columnDefs": [
        {
            "data": null,
            render: function (data, type, row) {
                return data[1];
            },
            "targets": 1
        },
        {
            "data": null,
            render: function (data, type, row) {
                if (data[4]) {
                    return '<button value="' + data[0] + '" type="button" name="desactivar" class="flex items-center justify-between text-2xl px-1 font-medium leading-5 text-emerald-500 rounded-lg focus:outline-none focus:shadow-outline-gray .btn-borrar" onclick="interruptor(this)"><i class="fas fa-toggle-on"></i></button>';
                }
                else {
                    return '<button value="' + data[0] + '" type="button" name="activar" class="flex items-center justify-between text-2xl font-medium px-1 leading-5 text-red-500 rounded-lg focus:outline-none focus:shadow-outline-gray .btn-borrar" onclick="interruptor(this)"><i class="fas fa-toggle-off"></i></button>';
                }
            },
            "targets": 3
        },
        {
            "data": null,
            render: function (data, type, row) {
                return '<a href="../sales/machines/index.php?visit_id='+data[0]+'" type="button" class="flex items-center justify-between px-2 py-2 text-lg font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray btn-editar" onclick="editarVisita(this)"><i class="fas fa-users"></i></a>';
            },
            "targets": 4
        },
        { 'visible': false, 'targets': [0] }
    ],
    "order": [[0, "desc"]]
});
