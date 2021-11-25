tablaReportes = $('#datatable-json').DataTable({
    language: { url: "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json" },
    ajax:{
        url: './functions.php',
        type: 'POST',
        data: {solicitud:'e'},
        dataSrc:""
    },
    columns: [
        { "data": "event_id" },
        { "data": "category_id" },
        { "data": "name" },
        { "data": "initial_date" },
        { "data": "final_date" },
        { "data": "number_hours" },
        { "data": "price" },
        {
            "data": null,
            render:function(data, type, row)
            {
                if(data['expenses'] == null){
                    return '0.00';
                }
                else{
                    return data['observation'];
                }
            },
            "targets": -1
        },
        {
            "data": null,
            render:function(data, type, row)
            {
                if(data['description_expenses'] == null){
                    return 'Sin descripción';
                }
                else{
                    return data['description_expenses'];
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