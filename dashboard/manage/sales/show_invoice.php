<?php
    session_start();

    if (empty($_GET['number'])) {
        header('Location: ../../index.php');
        die;
    }

    if (!array_key_exists('user_id', $_SESSION) || !array_key_exists('role_id', $_SESSION)) {
        header('Location: ../../index.php');
        die;
    }

    require_once '../../../app.php';

    $factura = $_GET['number'];

    $invoice = new Invoice();
    $invoice->get($factura);

    $user = new User();
    $user->get($invoice->getUserId());

    $customer = new Customer();
    $customer->getDetails($invoice->getCustomerId());

    $detalles = [];

	$membership_invoices = new MembershipInvoices();
	empty($membership_invoices->getToInvoice($invoice->getInvoiceId())) ? '' : array_push($detalles,($membership_invoices->getToInvoice($invoice->getInvoiceId())));

	$invoice_events = new InvoicesEvents();

	empty($invoice_events->getToInvoice($invoice->getInvoiceId())) ? '' : array_push($detalles,$invoice_events->getToInvoice($invoice->getInvoiceId()));

	$use_machines = new UseMachines();

	empty($use_machines->getToInvoice($invoice->getInvoiceId())) ? '' : array_push($detalles,($use_machines->getToInvoice($invoice->getInvoiceId())));
    
    $pagina[] = "gestionar";

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Factura # <?= $factura ?></title>
    <meta name="description" content="description here">
    <meta name="keywords" content="keywords,here">
    <link rel="icon" href="<?= constant('URL') ?>assets/img/fab.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <link href="<?= constant('URL') ?>assets/css/tailwind.output.css" rel="stylesheet">
    <script src="<?= constant('URL') ?>assets/js/templates/basetemplate.js" defer></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" defer></script>
    <script src="<?= constant('URL')?>assets/js/app/showinvoice.js" defer></script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <?php require_once '../../templates/header.php'; ?>

    <div class="container w-10/12 mx-auto pt-20">

        <div class="w-full px-4 md:px-0 md:mt-8 mb-16 text-gray-800 leading-normal">

            <div class="flex flex-row flex-wrap flex-grow">

                <div class="fixed z-10 overflow-y-auto top-0 w-full left-0 hidden min-height-100vh flex items-center justify-center h-screen" id="modal">
                    <div class="w-full pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity">
                            <div class="absolute inset-0 bg-new-bg"> </div>
                        </div>
                        <div class="inline-block align-center bg-white rounded text-left overflow-hidden border shadow transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                            <div class="border-b p-3 flex justify-between items-center">
                                <h5 class="font-bold uppercase text-gray-600">Detalles del servicio</h5>
                                <button class="border border-transparent focus:border-blue trans-all-linear" type="button" id="close">
                                    <svg
                                            class="w-8 h-8 text-grey hover:text-grey-dark"
                                            width="32"
                                            height="32"
                                            viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                                    </svg>
                                </button>
                            </div>
                            <div class="p-5" id="modal-content">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full p-3">
                    <div class="bg-white border rounded shadow">
                        <div class="border-b p-3">
                            <h5 class="font-bold uppercase text-gray-600">Detalles de factura</h5>
                        </div>
                        <div class="p-5 text-sm">
                            <div class="flex justify-between flex-wrap items-center leading-8">
                                <figure class="w-2/7">
                                    <img src="../../../assets/img/fab.png" alt="" srcset="" class="w-full">
                                </figure>
                                <div class="w-2/7">
                                    <p><span class="font-semibold">Factura Fablab:</span> # <?= $invoice->getInvoice()?></p>
                                    <?php if (empty($invoice->getReceipt())): ?>
                                        <label class="flex justify-between items-center my-2">
                                            <span class="text-gray-800 font-semibold">Recibo UP:</span>
                                            <div class="w-3/4 flex relative">
                                                <input type="number" class="text-sm w-full p-2 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Ingrese el número de recibo"
                                                name="numero_recibo" min="1">

                                                <button value="<?= $invoice->getInvoiceId() ?>" id="action" class="absolute inset-y-0 right-0 px-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-emerald-500 border border-transparent rounded-r-md active:bg-emerald-600 hover:bg-emerald-700">
                                                    <i class="fas fa-save"></i>
                                                </button>
                                            </div>
                                        </label>
                                    <?php else: ?>
                                        <p><span class="font-semibold">Recibo UP:</span> # <?= $invoice->getReceipt()?></p>
                                    <?php endif; ?>
                                    <p><span class="font-semibold">Fecha:</span> <?=$invoice->getDate()?></p>
                                    <a href="../../sales/download.php?factura=<?=$factura?>.pdf" target="_blank" class="inline-block w-full mt-2 px-3 py-2 text-sm font-semibold uppercase leading-5 text-center text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none">
                                    <i class="fas fa-file-pdf mr-3"></i>Descargar factura
                                    </a>
                                </div>
                            </div>
                            <hr class="my-6">
                            <div class="flex justify-between flex-wrap items-start leading-8">
                                <div class="w-3/5">
                                    <span class="font-semibold">Información del Cliente: </span>
                                    <p>Nombre del cliente: <?= $customer->getName() ?></p>
                                    <p>Código CIDETE: <?= $customer->getCode() ?></p>
                                    <p>
                                        <?php if ($customer->getDocumentType() == 'R'): ?>
                                            RUC:
                                        <?= $customer->getDocument()?>
                                        <?php elseif ($customer->getDocumentType() == 'C'): ?>
                                            Cedula:
                                        <?= $customer->getDocument()?>
                                        <?php else: ?>
                                            Pasaporte:
                                        <?= $customer->getDocument()?>
                                        <?php endif; ?>
                                    </p>
                                    <p>Correo: <a href="mailto:<?= $customer->getEmail() ?>"><?= $customer->getEmail() ?></a></p>
                                    <p>Dirección: <?= $customer->getProvince().', '. $customer->getCity().', '.$customer->getTownship() ?></p>
                                    <p>Telefono: <a href="tel:<?= $customer->getTelephone() ?>"><?= $customer->getTelephone() ?></a></p>
                                </div>
                                <div class="w-2/5">
                                    <span class="font-semibold">Información del Agente: </span>
                                    <p>Nombre del agente: <?= $user->getName() . ' ' . $user->getLastname() ?></p>
                                    <p>Correo: <a href="mailto:<?= $user->getEmail() ?>"><?= $user->getEmail() ?></a></p>
                                </div>
                            </div>
                            <hr class="my-6">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded">
                                <table class="w-full min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Descripción</th>
                                            <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Precio</th>
                                            <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Cantidad</th>
                                            <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Total</th>
                                            <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200" id="detalle_venta">
                                        <?php foreach ($detalles as $tabla => $entidad): ?>
                                            <?php foreach ($entidad as $registro => $valor): ?>
                                                <tr>
                                                    <td class="px-4 py-4 w-1/4">
                                                        <div class="flex items-center text-sm">
                                                            <p class="font-semibold"><?=  $valor['name']; ?></p>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 w-1/6">
                                                        <?= $valor['price']; ?>
                                                    </td>
                                                    <td class="px-4 py-4">
                                                        1
                                                    </td>
                                                    <td class="px-4 py-4 text-sm font-semibold">
                                                        <?= $valor['price']; ?>
                                                    </td>
                                                    <td class="px-4 py-4 flex items-center space-x-4 text-sm">
                                                        <button class="flex items-center justify-between px-2 py-2 text-xl font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray" onclick="getDetails(this,'<?= $valor['service'] ?>')" value="<?= $valor['id'] ?>">
                                                            <i class="fas fa-search"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>

                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot class=" bg-gray-100 divide-y divide-gray-200" id="detalle_totales">
                                        <tr>
                                            <td class="px-4 py-3 text-sm font-semibold"></td>
                                            <td class="px-4 py-3 text-sm font-semibold"></td>
                                            <td colspan="1" class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider">TOTAL</td>
                                            <td class="px-4 py-3 text-sm font-semibold"><?= $invoice->getTotal() ?></td>
                                            <td class="px-4 py-3 text-sm font-semibold"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>