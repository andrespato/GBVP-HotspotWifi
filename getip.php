<?php

echo "IP: ".gethostbyname(trim(`hostname`))." SERVER: ".$_SERVER['REMOTE_ADDR'];

 ?>
