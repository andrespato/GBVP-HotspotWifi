<?php
// LISTAGEM DE ACESSOS
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



$sql = "SELECT * FROM bit_wifi_lofin.visitantes ORDER BY visitantes.date DESC LIMIT ".$_POST['total'].";";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

while($row = mysqli_fetch_array($result)){
  if($row['ativo'] == 3) $state = "Validado";
  else $state = "Não validado";

  echo "<tr>
            <td>" . $row['date'] . "</td>
            <td>" . $row['type'] . "</td>
            <td>" . $row['email'] . "</td>
            <td>" . $row['telem'] . "</td>
            <td>" . $row['name'] . "</td>
            <td>" . $row['gender'] . "</td>
            <td>" . $row['hometown'] . "</td>
            <td>" . $row['profile'] . "</td>
            <td>" . $row['hash_code'] . "</td>
            <td>" . $row['try'] . "</td>
            <td>" . $state  . "</td>
            <td>" . $row['centroEnot']  . "</td>
        </tr>";
}

mysqli_close($conn);
 ?>
