<?php
  //создаем какое-то имя, ну, например: 
   $name = 'bugs_report/' . date('l jS \of F Y h_i_s A') . '.png';
  //записываем, не забывая перекодировать из base64
  file_put_contents($name, base64_decode($_POST['data'] ));
  //отдаем обратно имя созданного файла 
  echo( "http://". $_SERVER['SERVER_NAME'] . "/" . $name );
?>