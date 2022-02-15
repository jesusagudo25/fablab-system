<?php

    if (!array_key_exists('user_id', $_SESSION) || !array_key_exists('role_id', $_SESSION)) {
        header('Location: ../../index.php');
        die;
    }

    use Dompdf\Dompdf;
    use Dompdf\Options;

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    $invoice = new Invoice();
    $invoice->getLastID();

    $user = new User();
    $user->get($invoice->getUserId());

    $customer = new Customer();
    $customer->getDetails($invoice->getCustomerId());

    if($customer->getEmail()){

        $detalles = [];

        $options = new Options();
        $options->set('isRemoteEnabled', TRUE);

        // instantiate and use the dompdf class
        $dompdf = new Dompdf($options);

        require_once '../templates/factura.php';

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $output = $dompdf->output();
        file_put_contents('./factura_'.$invoice->getInvoice().'.pdf', $output);

        //-----

        $mail = new PHPMailer(true);
        $mail->AddEmbeddedImage('../../assets/img/flp.png', 'logo_e');
        $mail->AddEmbeddedImage('../../assets/img/fab.jpg', 'logo_c');
        $mail->AddAttachment('./factura_'.$invoice->getInvoice().'.pdf', 'factura_'.$invoice->getInvoice().'.pdf');

        $html = '<!DOCTYPE html>
<html lang="es">

<head>
  <title>Salted | A Responsive Email Template</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <style type="text/css">
    /* CLIENT-SPECIFIC STYLES */
    #outlook a{padding:0;} /* Force Outlook to provide a "view in browser" message */
    .ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Hotmail to display emails at full width */
    .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing */
    body, table, td, a{-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;} /* Prevent WebKit and Windows mobile changing default text sizes */
    table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;} /* Remove spacing between tables in Outlook 2007 and up */
    img{-ms-interpolation-mode:bicubic;} /* Allow smoother rendering of resized image in Internet Explorer */

    /* RESET STYLES */
    body{margin:0; padding:0;}
    img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
    table{border-collapse:collapse !important;}
    body{height:100% !important; margin:0; padding:0; width:100% !important;}

    /* iOS BLUE LINKS */
    .appleBody a {color:#68440a; text-decoration: none;}
    .appleFooter a {color:#999999; text-decoration: none;}

    /* MOBILE STYLES */
    @media screen and (max-width: 525px) {

        /* ALLOWS FOR FLUID TABLES */
        table[class="wrapper"]{
          width:100% !important;
        }

        /* ADJUSTS LAYOUT OF LOGO IMAGE */
        td[class="logo"]{
          text-align: left;
          padding: 20px 0 20px 0 !important;
        }

        td[class="logo"] img{
          margin:0 auto!important;
        }

        /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
        td[class="mobile-hide"]{
          display:none;}

        img[class="mobile-hide"]{
          display: none !important;
        }

        img[class="img-max"]{
          max-width: 100% !important;
          height:auto !important;
        }

        /* FULL-WIDTH TABLES */
        table[class="responsive-table"]{
          width:100%!important;
        }

        /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
        td[class="padding"]{
          padding: 10px 5% 15px 5% !important;
        }

        td[class="padding-copy"]{
          padding: 10px 5% 10px 5% !important;
          text-align: center;
        }

        td[class="padding-meta"]{
          padding: 30px 5% 0px 5% !important;
          text-align: center;
        }

        td[class="no-pad"]{
          padding: 0 0 20px 0 !important;
        }

        td[class="no-padding"]{
          padding: 0 !important;
        }

        td[class="section-padding"]{
          padding: 50px 15px 50px 15px !important;
        }

        td[class="section-padding-bottom-image"]{
          padding: 50px 15px 0 15px !important;
        }

        /* ADJUST BUTTONS ON MOBILE */
        td[class="mobile-wrapper"]{
            padding: 10px 5% 15px 5% !important;
        }

        table[class="mobile-button-container"]{
            margin:0 auto;
            width:100% !important;
        }

        a[class="mobile-button"]{
            width:80% !important;
            padding: 15px !important;
            border: 0 !important;
            font-size: 16px !important;
        }

    }
  </style>
</head>

<body style="margin: 0; padding: 0;">

  <!-- HEADER -->
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td bgcolor="#ffffff">
        <div align="center" style="padding: 0px 15px 0px 15px;">
          <table border="0" cellpadding="0" cellspacing="0" width="500" class="wrapper">
            <!-- LOGO/PREHEADER TEXT -->
            <tr>
              <td style="padding: 20px 0px 30px 0px;" class="logo">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                  <tr>
                    <td bgcolor="#ffffff" width="100" align="left" style="vertical-align: middle">
                      <a href="#" target="_blank"><img alt="Logo" src="cid:logo_c" width="140" height="50" style="display: inline-block; font-family: Helvetica, Arial, sans-serif; color: #666666; font-size: 16px;" border="0"></a>
                    </td>
                    <td bgcolor="#ffffff" width="400" align="right" class="mobile-hide" style="vertical-align: middle">
                      <table border="0" cellpadding="0" cellspacing="0">
                        <tr style="vertical-align: middle">
                          <td align="right" style="padding: 0 0 5px 0; font-size: 14px; font-family: Arial, sans-serif; color: #666666; text-decoration: none;"><span style="color: #666666; text-decoration: none;">Hola '.$customer->getName().'
							<br>Factura: #'.$invoice->getInvoice().'
							</span></td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </div>
      </td>
    </tr>
  </table>

  <!-- ONE COLUMN SECTION -->
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td bgcolor="#ffffff" align="center" style="padding: 70px 15px 70px 15px;" class="section-padding">
        <table border="0" cellpadding="0" cellspacing="0" width="500" class="responsive-table">
          <tr>
            <td>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>
                    <!-- HERO IMAGE -->
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td class="padding-copy">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td style="text-align: center;">
                                  <a href="#" target="_blank" style="text-align: center;"><img src="cid:logo_e" width="70%" height="200" border="0" alt="Can an email really be responsive?" style="display: inline-block; padding: 0; color: #666666; text-decoration: none; font-family: Helvetica, arial, sans-serif; font-size: 16px;"
                                    class="img-max"></a>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td>
                    <!-- COPY -->
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="center" style="font-size: 25px; font-family: Helvetica, Arial, sans-serif; color: #333333; padding-top: 30px;" class="padding-copy">Gracias por comprar con nosotros</td>
                      </tr>
                      <tr>
                        <td align="center" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">Conserve este correo electrónico, ya que contiene detalles sobre su compra. Le adjuntamos la factura correspondiente.</td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td>
                    <!-- BULLETPROOF BUTTON -->
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="mobile-button-container">
                      <tr>
                        <td align="center" style="padding: 25px 0 0 0;" class="padding-copy">
                          <table border="0" cellspacing="0" cellpadding="0" class="responsive-table">
                            <tr>
                              <td align="center"><a href="#" target="_blank" style="font-size: 16px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #ffffff; text-decoration: none; background-color: #5D9CEC; border-top: 15px solid #5D9CEC; border-bottom: 15px solid #5D9CEC; border-left: 25px solid #5D9CEC; border-right: 25px solid #5D9CEC; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; display: inline-block;"
                                class="mobile-button">Visita nuestro sitio web &rarr;</a></td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <!-- FOOTER -->
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td bgcolor="#ffffff" align="center">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td style="padding: 20px 0px 20px 0px;">
              <!-- UNSUBSCRIBE COPY -->
              <table width="500" border="0" cellspacing="0" cellpadding="0" align="center" class="responsive-table">
                <tr>
                  <td align="center" valign="middle" style="font-size: 12px; line-height: 18px; font-family: Helvetica, Arial, sans-serif; color:#666666;">
                    <span class="appleFooter" style="color:#666666;">Canto del Llano, Santiago, Veraguas, Panamá</span>
                    <br>
                    <a
                    style="color: #666666; text-decoration: none;">FABLAB CIDETE CRUV</a>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

</body>

</html>';

        $mail->SMTPDebug = SMTP::DEBUG_OFF;

        $mail->isSMTP();

        $mail->Host = "smtp.hostinger.com";

        $mail->SMTPAuth = true;

        $mail->Username = "jagudo@explorando.xyz";
        $mail->Password = "FabLab*22";

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->Port = 587;

        $mail->From = "jagudo@explorando.xyz";
        $mail->FromName = "FabLab";

        $mail->addAddress($customer->getEmail(), $customer->getName());

        $mail->isHTML(true);

        $mail->Subject = "Su compra en Fablab #".$invoice->getInvoice();
        $mail->Body = $html;

        $mail->CharSet = 'UTF-8';

        try {
            $mail->send();
        } catch (Exception $e) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
        unlink('./factura_'.$invoice->getInvoice().'.pdf');
    }



