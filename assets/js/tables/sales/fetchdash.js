tablaVentas = $('#datatable-json').DataTable({
    language: { url: "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json" },
    "processing": true,
    "serverSide": true,
    "ajax": "./ajax.php",
    "columnDefs": [
        {
            "data": null,
            render:function(data, type, row)
            {
                return '<div class="flex items-center"><a href="./sales/download.php?factura='+data[0]+'.pdf" target="_blank" class="flex items-center px-2 py-2 text-lg font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray"><i class="fas fa-file-pdf"></i></a></div>';
            },
            "targets": 4
        }
    ],
    "order": [[ 0, "desc" ]]
});