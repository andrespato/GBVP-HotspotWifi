<?php
error_reporting(E_ALL);
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

// GUARDA ID E HASH INSERIDO PELO USER
$data = explode("-",$_POST["postdata"]);
$rowID = $data[0];
$user_hash = $data[1];

// EXEC QUERY, GUARDA ROW DATA NUM ARRAY E LIBERTA $result
$sql = "SELECT id, ativo, try, hash_code FROM bit_wifi_lofin.visitantes WHERE id =".$rowID.";";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$rowdata = mysqli_fetch_assoc($result);

// GUARDA ROWDATA EM VARIAVEIS
$bd_hash = $rowdata["hash_code"];
$ativo = $rowdata["ativo"];
$try = $rowdata["try"];


$try++;
if($ativo == 0){
    $ativo = 1;
}

//echo $bd_hash." - ". $ativo ." - ". $try."<br/>";

if($ativo == 2){
    echo 400;
}
else if($try <= 3 && $ativo <= 1){
    if(strcasecmp($user_hash,$bd_hash) == 0){
        $ativo = 3;
        $sql = "UPDATE bit_wifi_lofin.visitantes SET ativo=".$ativo.", try=".$try." WHERE id=".$rowID.";";
        $res = mysqli_query($conn, $sql) or die(mysqli_error($conn));
        echo 500;
    }
    else{
        if($try == 3){
            $ativo = 2;
            $sql = "UPDATE bit_wifi_lofin.visitantes SET ativo=".$ativo.", try=".$try." WHERE id=".$rowID.";";
            $res = mysqli_query($conn, $sql) or die(mysqli_error($conn));
            echo 400;
        }
        else{
            $sql = "UPDATE bit_wifi_lofin.visitantes SET ativo=".$ativo.", try=".$try." WHERE id=".$rowID.";";
            $res = mysqli_query($conn, $sql) or die(mysqli_error($conn));
            echo $try;
        }
    }
}
mysqli_close($conn);
?>
