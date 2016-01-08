<?php
if ($_POST) {
  foreach ($_POST as $key => $value) {
    switch ($key) {
        case 'menu' :
            file_put_contents("../content/menu.html", $value);
            break;
    }
  }
  header ("Location: " . $_SERVER['PHP_SELF']);
}
$menu = file_get_contents("../content/menu.html");
?>
<h1>Меню</h1>
<form method="POST">
<textarea cols=100 rows=10 name="menu"><?php echo $menu; ?></textarea>
<input type="submit">
</form>