<?php
  $name = '../bugs_report/' . date('l jS \of F Y h_i_s A') . '.png';
  file_put_contents($name, base64_decode($_POST['data'] ));
  echo( "http://". $_SERVER['SERVER_NAME'] . "/" . $name );
?>