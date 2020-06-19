<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


function send_mail($order_number,$order) {
    require_once 'phpmailer/PHPMailer.php';
    require_once 'phpmailer/SMTP.php';
    require_once 'phpmailer/Exception.php';
    $mail = new PHPMailer();

    $totalAmount = 0;

    $body = "
        <table border='1' cellpadding='5' cellspacing='0' style='table-layout: fixed; min-width: 500px; max-width: 600px; width: 70%'>
        <tr>
            <td colspan='2'><b style='font-size: 20px'>Nuevo pedido recibido</b></td>
        </tr>
        <tr>
            <td><b>Nombre:</b></td><td>".$order['client']['firstname']."</td>
        </tr>
        <tr>
            <td><b>Apellido:</b></td><td>".$order['client']['lastname']."</td>
        </tr>
        <tr>
            <td><b>Teléfono:</b></td><td><a href='https://wa.me/".whatsappValidNumber($order['client']['phone'])."'>".$order['client']['phone']."</a>
                                     <br><a href='https://web.whatsapp.com/send?phone=".whatsappValidNumber($order['client']['phone'])."'>web.whatsapp.com</a></td>
        </tr>
        <tr>
            <td colspan='2'>
                <table border='1' cellpadding='5' cellspacing='0' style='table-layout: fixed; width: 100%'>
                    <tr>
                        <td colspan='6'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan='6'>Datos del pedido</td>
                    </tr>
                    <tr>
                        <td><b>Cantidad</b></td>
                        <td><b>Categoria</b></td>
                        <td><b>Producto</b></td>
                        <td><b>Talle</b></td>
                        <td><b>Color</b></td>
                        <td><b>Precio</b></td>
                    </tr>
                    ";
        foreach ($order['orderItems'] as $prod){
            $totalAmount += $prod['price'];
            $body.= "<tr>
                        <td>".$prod['quantity']."</td>
                        <td>".$prod['category_name']."</td>
                        <td>".$prod['id']." - ".$prod['name']."</td>
                        <td>".$prod['sizeName']."</td>
                        <td>".$prod['colorName']."</td>
                        <td>$".$prod['price']."</td>
                     </tr>";
        }
    $body .="
                    <tr style='background-color: black; color: white'>
                        <td colspan='5'><b>TOTAL</b></td><td><b>$$totalAmount</b></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    
    ";

    $mail->isSMTP();
    $mail->SMTPDebug = 2;
    $mail->SMTPAuth = true;
//    $mail->SMTPSecure = "ssl";
    $mail->Host = "smtp.hostinger.com.ar";
    $mail->Port = 587;
    $mail->Username = "info@serk.com.ar";
    $mail->Password = "serk2020";
    $mail->SetFrom('info@serk.com.ar', 'Serk Online');
    $mail->isHTML(true);
    $mail->Subject = "Nueva orden registrada - Nro ".$order_number;
    $mail->Body = $body;
    $mail->AddAddress("nereaschmidt@hotmail.com");
    $mail->AddAddress("colomuller91@gmail.com");
    $mail->CharSet = 'UTF-8';
    $mail->send();

}

function whatsappValidNumber($numero){
    $numero = ltrim($numero,'0');
    $numero = str_replace(' ','',$numero);
    $numero = str_replace('+','',$numero);
    $numero = str_replace('-','',$numero);
    $numero = trim($numero);
    if (strpos($numero,'54') !== 0){
        $numero = '54'.$numero;
    }
    return $numero;
}

?>
