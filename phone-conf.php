<?php
error_reporting(E_ALL);
require 'phpmailer/PHPMailerAutoload.php';
// mysql connect ____________________________________________________________
    $user = 'root';
    $password = 'B954dm1n';
    $db = 'bit_wifi_login';
    $host = '192.168.100.20';
    $port = 3306;

    $conn = new mysqli("$host:$port", $user, $password);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
//______________________________________________________________________________________

// GUARDAR NUMERO DE TELEMOVEL E HASH NA BD
$random_hash = substr(md5(uniqid(rand(), true)), 5, 5);
$data = explode("-",$_POST["postdata"]);
$numTelem = $data[1];
$rowID = $data[0];
$address = $numTelem."@smsbvp.bit.local";

$sql = "UPDATE bit_wifi_lofin.visitantes SET ativo=0, hash_code='".$random_hash."',telem='".$numTelem."', try=0
        WHERE id =".$rowID.";";

$query_exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

//ENVIAR EMAIL/SMS COM HASH CODE
$mail = new PHPMailer;

//$mail->SMTPDebug = 2;                               // Enable verbose debug output

$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);


$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smsbvp.bit.local';  					// Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'smsbvp@bit.local';                 // SMTP username
$mail->Password = 'smsBVP';                           // SMTP password
//$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 25;                                    // TCP port to connect to
$mail->SMTPAuth = false;
$mail->SMTPSecure = false;
$mail->CharSet = 'UTF-8';

$mail->setFrom('andre.pato@bacalhoa.pt', 'GBVP - Wifi Grátis');
$mail->addAddress($address, 'Visitante');     // Add a recipient
//$mail->addCC('paulo.costa@bacalhoa.pt');

// $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'GBVP - Hotspot Wifi';
$mail->Body    ="<h1> GBVP - Hotspot Wifi ! Código de Confirmação :</h1> <b>".$random_hash."</b>";

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo '<br/>Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'E-Mail foi enviado';
}


?>
