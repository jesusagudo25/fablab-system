tablaReportes = $('#datatable-json').DataTable({
    language: { url: "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json" },
    ajax:{
        url: './functions.php',
        type: 'POST',
        data: {solicitud:'v'},
        dataSrc:""
    },
    columns: [
        { "data": "visit_id" },
        { "data": "customer_id" },
        { "data": "reason_id" },
        {
            "data": null,
            render:function(data, type, row)
            {
                if(data['time']){
                    return '<span class="inline-flex px-2 text-xs font-medium leading-5 rounded-full text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100">Ver areas</span>';
                }
                else{
                    return '<span class="inline-flex px-2 text-xs font-medium leading-5 rounded-full text-red-700 bg-red-100 dark:text-red-100 dark:bg-red-700">Sin areas</span>';
                }
            },
            "targets": -1
        },
        { "data": "date" },
        {
            "data": null,
            render:function(data, type, row)
            {
                if(data['observation'] == null){
                    return 'Sin observación';
                }
                else{
                    return data['observation'];
                }
            },
            "targets": -1
        }
    ],
    responsive: true,
    processing: true,
    'columnDefs' : [
        //hide the second & fourth column
        { 'visible': false, 'targets': [0] }
    ],
    order: [[ 0, "desc" ]]
});