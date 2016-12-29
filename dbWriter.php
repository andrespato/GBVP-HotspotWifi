<?php
// mysql connect ____________________________________________________________
    $user = 'root';
    $password = 'B954dm1n';
    $db = 'bit_wifi_lofin';
    $host = '192.168.100.20';
    $port = 3306;

    $conn = new mysqli("$host:$port", $user, $password);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

//______________________________________________________________________________________

// FIELD CHECK
$postArr = $_POST;
$hometownArr = $postArr['response']['hometown'];

if(isset($postArr['email'])) {
    $email = $postArr['email'];
}
else{
    $email = $postArr['response']['email'];
    }

$name =  $postArr['response']['name'];
$gender = $postArr['response']['gender'];
$profileID = $postArr['response']['id'];
$hometown = $hometownArr['name'];
$locale = $postArr['response']['locale'];
$ativo = 0;

if(strcmp($_GET['type'],'fb') == 0){
    $ativo = 3;
}

$centroEnot = $_GET['centroEnot'];

//var_dump($_POST);

 $sql = "INSERT INTO bit_wifi_lofin.visitantes (email,date,name,gender,hometown,locale,type,profile,ativo,centroEnot)
        VALUES('".$email."',NOW(),'".$name."','".$gender."','".$hometown."','".$locale."','".$_GET['type']."','".$profileID."','".$ativo."','".$centroEnot."');";


$query_exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

printf (mysqli_insert_id($conn));

mysqli_close($conn);
?>
