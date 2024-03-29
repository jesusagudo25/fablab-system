$(document).ready(function () {
    $.ajax({
        url: './functions.php',
        type: 'POST',
        data: {solicitud:'s'},
        success: function (data) {
            var table = $('#datatable-json').DataTable({
                language: { url: "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json" },
                data: data,
                columns: [
                    { "data": "invoice_id" },
                    { "data": "customer_id" },
                    { "data": "user_id" },
                    { "data": "date" },
                    { "data": "total" },
                    {
                        "data": null,
                        render:function(data, type, row)
                        {
                            return '<div class="flex items-center"> <a href="../../sales/download.php?factura='+data['invoice_id']+'.pdf" target="_blank" class="flex items-center px-2 py-2 text-lg font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray"><i class="fas fa-file-pdf"></i></a></div>';
                        },
                        "targets": -1
                    }
                ],
                responsive: true,
                processing: true
            });
        }
    });
});