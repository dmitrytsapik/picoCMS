<?php
abstract class Page_Template {
    const top_button = "<div id=\"toTop\">&uarr; НАВЕРХ </div>";
    const reporting_button = "";// = "<div id=\"report_bug\">Ошибка в отображении сайта? - Жмите сюда!</div>"; 
    protected function Header_Page() {
        echo '<!DOCTYPE html>
              <html>
              <head>
                <link rel="stylesheet" href="/css/style.css">
                <link rel="icon" href="http://www.crimea.edu/favicon.ico">
                <meta charset="utf-8">
                <meta name="description" content="' . file_get_contents('content/description') . '">
                <meta name="keywords" content="' . file_get_contents('content/keywords') . '">
                <meta name="author" lang="en" content="Dmitry Tsapik, Vladislav Suprun">
                <meta name="google-site-verification" content="G_cfwB-mweujBALcMpfubkpguMN8GMFLSAxIfzCrHEI" />                <meta name=\'yandex-verification\' content=\'4220f11129c2d620\' />
                <title>Физико-технический институт | ФГАОУ ВО "КФУ им. В. И. Вернадского"</title>
                <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
                <script src="/js/main.js"></script>
              </head>';
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