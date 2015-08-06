<?php
abstract class Page_Template {
    const top_button = "<div id=\"toTop\">&uarr; наверх </div>";
    const reporting_button = "<div id=\"report_bug\">Ошибка в отображении сайта? - Жмите сюда!</div>"; 
    protected function Header_Page() {
        echo file_get_contents("main/header.html") .
             "\n" . file_get_contents("main/menu.html") .
             "\n   <article>\n";
    }
    protected function files_list($dir) {
        $files = scandir($dir);
        unset($files[0], $files[1]);
        arsort($files);
        $files = array_values($files);
        return $files;
    }
    protected function Footer_Page() {
        $additional_content = self::top_button . self::reporting_button;
        echo "      </article>\n" .
        "<footer>\n" .
        file_get_contents("content/footer") .
        "</footer>" .
        $additional_content .
        "</body>
         </html>";
    }
}
?>