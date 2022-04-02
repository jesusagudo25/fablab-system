tablaVentas = $('#datatable-json').DataTable({
    language: { url: "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json" },
    "processing": true,
    "serverSide": true,
    "ajax": "./functions.php",
    "columnDefs": [
        {
            "data": null,
            render:function(data, type, row)
            {
                if(data[1]){
                    return data[1];
                }
                else{
                    return '<div><span class="inline-flex px-2 text-xs font-medium leading-5 rounded-full text-red-700 bg-red-100">Sin asignar</span></div>';
                }
            },
            "targets": 1
        },
        {
            "data": null,
            render:function(data, type, row)
            {
                return '<div class="flex items-center"><a href="./show_invoice.php?number='+data[0]+'" class="flex items-center px-2 py-2 text-lg font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray"><i class="fas fa-search"></i></a></div>';
            },
            "targets": 5
        }
    ],
    "order": [[ 0, "desc" ]]
});