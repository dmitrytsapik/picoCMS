<script src="//tinymce.cachefly.net/4.2/tinymce.min.js"></script>
<script type="text/javascript">
  tinymce.init({
    add_unload_trigger: false,
    mode : "textareas",
    height : 600,
    width: 980,
    selector: "#tinymce",
    plugins: [ "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
               "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
               "save table contextmenu directionality emoticons template paste textcolor" ]
  });
</script>
<?php
function search_event($location) {
    $ret ="";
    $files = scandir($location);
      unset($files[0], $files[1]);
      asort($files);
    $files = array_values($files);
      foreach ($files as $event) {
        $ret .= "<trstyle=\"border: 1px solid black;\"><th colspan=2 >". $event . "</th></tr>";
        $files_category = scandir($location . "/" . $event);
        unset($files_category[0], $files_category[1]);
        asort($files_category);
        $ret .= "<tr style=\"border: 1px solid black;\">" . 
                  "<td>" .
                      "<form method=\"GET\">" .
                          "<select name=\"item\">";
        foreach ($files_category as $file) {
             $ret .= "<option value=\"" . $event . "/" . $file . "\">" . $file . "</option>";
        }
        $ret .= "\n</select>" .
                "<input type=\"submit\" value=\"Редактировать\">" .
                "</form>" .
                "</td>".
                "<td>" .
                "<form method=\"POST\">" .
                  "<input type=\"hidden\" name=\"folder\" value=\"". $event ."\">".
                  "<input size=20 type=\"text\" name=\"new_file\">" .
                  "<input type=\"submit\" value=\"Добавить страницу\">" .
                "</form>" .
              "</td>" .
            "</tr>";
    }
    return $ret;
  }

if ($_POST) {
  foreach ($_POST as $key => $value) {
    switch ($key) {
      case 'edit_page' :
        file_put_contents("../pages/" . $value, $_POST['area']);
      break;
      case 'new_batch' :
        mkdir("../pages/" . $value, 0700);
        file_put_contents("../pages/" . $value . "/menu.html", "<p><b><a href=\"/?p=" . $value . "\">Gary Moore - Empty Rooms</a></b></p>");
        file_put_contents("../pages/" . $value . "/index.html", "<iframe width=\"640\" height=\"480\" src=\"https://www.youtube.com/embed/NGyYjuteLUk\" frameborder=\"0\" allowfullscreen></iframe>");
      break;
      case 'new_file' :
        file_put_contents("../pages/" . $_POST['folder'] . "/" . $value . ".html", "<iframe width=\"640\" height=\"480\" src=\"https://www.youtube.com/embed/NGyYjuteLUk\" frameborder=\"0\" allowfullscreen></iframe>");
      break;
    }
  }
  header ("Location: ".$_SERVER['PHP_SELF']);
  exit;
}

if(isset($_GET['item'])) {
  echo "<a href=\"?\">Назад</a>" .
        "<form method=\"POST\">" .
        "<input type=\"hidden\" name=\"edit_page\" value=\"" . $_GET['item'] ."\">" .
        "<textarea id=\"tinymce\" name=\"area\">" .
        file_get_contents("../pages/" . $_GET['item'])
        . "</textarea>" .
        "<input type=\"submit\" value=\"Редактировать\">".
        "</form>";
  exit;
}
echo "<form method=\"POST\">" .
        "<input size=20 type=\"text\" name=\"new_batch\">" .
        "<input type=\"submit\" value=\"Добавить группу\">" .
      "</form>" .  
      "<table style=\"border-style: solid; border-width: 1px; border-collapse: collapse;\">".
        search_event("../pages/") .
      "</table>";
?>