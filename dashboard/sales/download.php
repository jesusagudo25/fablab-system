<?php

    require_once '../../app.php';

    session_start();

    if (!array_key_exists('user_id', $_SESSION) || !array_key_exists('role_id', $_SESSION)) {
        header('Location: ../../index.php');
        die;
    }

    use Dompdf\Dompdf;
    use Dompdf\Options;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $invoice = new Invoice();
    $invoice->get($_REQUEST['factura']);

    $user = new User();
    $user->get($invoice->getUserId());

    $customer = new Customer();
    $customer->get($invoice->getCustomerId());

    $detalles = [];

    $options = new Options();
    $options->set('isRemoteEnabled', TRUE);

    // instantiate and use the dompdf class
    $dompdf = new Dompdf($options);

    require_once '../templates/factura.php';

    $dompdf->loadHtml($html);

    $dompdf->setPaper('A4', 'portrait');

    $dompdf->render();

    $fecha = new DateTime();
    $filename = $fecha->getTimestamp().'_'.'sales_'.$invoice->getInvoice();

    // Output the generated PDF to Browser
    $dompdf->stream($filename, array("Attachment" => 0));
}