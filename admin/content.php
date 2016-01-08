<?php
if ($_POST) {
  foreach ($_POST as $key => $value) {
    switch ($key) {
        case 'description' :
            file_put_contents("../content/description", $value);
            break;
        case 'keywords' :
            file_put_contents("../content/keywords", $value);
            break;
        case 'default_header' :
            file_put_contents("../content/header.html", $value);
            break;
        case 'default_footer' :
            file_put_contents("../content/youtube", $value);
            break;
        case 'footer' :
            file_put_contents("../content/footer.html", $value);
            break;
    }
  }
  header ("Location: " . $_SERVER['PHP_SELF']);
}
$description = htmlspecialchars(file_get_contents("../content/description"));
$keywords = htmlspecialchars(file_get_contents("../content/keywords"));
$default_header = htmlspecialchars(file_get_contents("../content/header.html"));
$default_footer = htmlspecialchars(file_get_contents("../content/youtube"));
$footer = htmlspecialchars(file_get_contents("../content/footer.html"));
?>
<h1>Описание сайта</h1>
<form method="POST">
<input size=100 name="description" value="<?php echo $description; ?>">
<input type="submit">
</form>
<h1>Ключевые слова</h1>
<form method="POST">
<textarea cols=100 rows=3 name="keywords"><?php echo $keywords; ?></textarea>
<input type="submit">
</form>
<h1>Тело верх</h1>
<form method="POST">
<textarea cols=100 rows=10 name="default_header"><?php echo $default_header; ?></textarea>
<input type="submit">
</form>
<h1>Youtube</h1>
<form method="POST">
<textarea cols=100 rows=10 name="default_footer"><?php echo $default_footer; ?></textarea>
<input type="submit">
</form>
<h1>Тело низ</h1>
<form method="POST">
<textarea cols=100 rows=10 name="footer"><?php echo $footer; ?></textarea>
<input type="submit">
</form>