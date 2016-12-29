<?php
// UniFi Connection details
$unifi = array(
          'unifiServer' => "https://192.168.100.24:8843/",
          'unifiUser'   => "admin",
          'unifiPass'   => "Mko0909ijn"
        );

$id = 0;
$minutes= 180;

//function sendAuthorization($id, $minutes, $unifi) {
  // Start Curl for login
  $ch = curl_init();
  // Return output instead of displaying it
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  // We are posting data
  curl_setopt($ch, CURLOPT_POST, TRUE);
  // Set up cookies
  $cookie_file = "/tmp/unifi_cookie";
  curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
  // Allow Self Signed Certs
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  curl_setopt($ch, CURLOPT_SSLVERSION, 1);
  // Login to the UniFi controller
  curl_setopt($ch, CURLOPT_URL, $unifi['unifiServer']."/api/login");
  $data = json_encode(array("username" => $unifi['unifiUser'],"password" => $unifi['unifiPass']));
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
  curl_exec($ch);
  // Send user to authorize and the time allowed
  $data = json_encode(array(
          'cmd'=>'authorize-guest',
          'mac'=>$id,
          'minutes'=>$minutes));
  // Make the API Call
  curl_setopt($ch, CURLOPT_URL, $unifi['unifiServer'].'/api/s/default/cmd/stamgr');
  curl_setopt($ch, CURLOPT_POSTFIELDS, 'json='.$data);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
  curl_exec ($ch);

  // Logout of the connection
  curl_setopt($ch, CURLOPT_URL, $unifi['unifiServer']."/logout");
  curl_exec ($ch);
  curl_close ($ch);
  sleep(6); // Small sleep to allow controller time to authorize
//}



 ?>
