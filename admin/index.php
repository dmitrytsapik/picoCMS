<?php
echo <<<HTML
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Панель инструментов администратора</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  	<style>
  		body {
  			font-family: Arial;
  		}
  		h1 {
  			text-align: center;
  		}
  		.warning {
  			color:red;
  			text-align:center;
  			font-size: 2em;
  		}
  	</style>
  </head>
  <body>
    <h1>Добро пожаловать в панель управления picoCMS!</h1>
    <ul>
    	<li><a href="menu.php">Меню</a></li>
    	<li><a href="pages.php">Категории и страницы</a></li>
    	<li><a href="content.php">Шапка и подвал</a></li>
    	<li><a href="upload.php">Загрузчик файлов</a></li>
    </ul>
HTML;
if(!file_exists(".htaccess") || !file_exists(".htpasswd")) {
	echo "<div class=\"warning\">Внимание!!! Отсутствуют .htaccess или .htpasswd. Возможен несанкционированный доступ к панели администратора!</div>";
}
/*include "apr1-md5.php";
$dir = dirname(__FILE__);
echo "Полный путь: " . $dir . "</p>";

// Password to be used for the user
$username = 'user1';
$password = 'password1';
 
// Encrypt password
$encrypted_password = crypt_apr1_md5($password);
 
// Print line to be added to .htpasswd file
echo $username . ':' . $encrypted_password;*/
echo <<<HTML
  </body>
  </html>
HTML;
?>