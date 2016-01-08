<?php
abstract class Page_Template {
    const top_button = "<div id=\"toTop\">&#9650; Наверх</div>";
    const reporting_button = "";// = "<div id=\"report_bug\">Ошибка в отображении сайта? - Жмите сюда!</div>"; 
    protected function Menu_Read($path) {
        $lines = file($path);
        $html = "";
        $trigger_lines = false;
        foreach ($lines as $line) {
            $line = trim($line);
            switch($line)
                {
                    case (strstr($line, '--')):
                        $line = explode("|", str_replace('--', '', $line));
                        $html .= "<a href=\"" . ((isset($line[1]))?trim($line[1]):"#") . "\">" . trim($line[0]) . "</a>\n";
                    break;
    
                    case (strstr($line, '@')):
                        $line = explode("%", str_replace('@', '', $line));
                        $html .= "<h3" . ((isset($line[1]))?" class=\"". trim($line[1]) . "\"":"") . ">" . trim($line[0]) . "</h3>\n";
                    break;
    
                    case (strstr($line, '#')):
                        $html .=  "</div>\n<div class=\"nav-column\">\n";
                    break;
    
                    default:
                        $line = explode("|", $line);
                        if($trigger_lines==true) { 
                            $html .= "</div>\n</div>\n</li>\n";
                            $trigger_lines = false;
                        }
                        $html .= "<li>\n<a href=\"" . ((isset($line[1]))?trim($line[1]):"#") . "\">" . trim($line[0]) . "</a>\n";
                        if(!isset($line[1])) {
                            $html .= "<div>\n<div class=\"nav-column\">\n";
                            $trigger_lines = true;
                        }
                        if(isset($url[1])) $html .= "</li>";
                    break;
                }
            }
            return $html;
    }
    protected function Header_Page($title) {
        echo '<!DOCTYPE html>
              <html>
              <head>
                <link rel="stylesheet" href="/css/style.css">
                <link rel="icon" href="/images/logo.png">
                <meta charset="utf-8">
                <meta name="description" content="' . file_get_contents('content/description') . '">
                <meta name="keywords" content="' . file_get_contents('content/keywords') . '">
                <meta name="author" lang="en" content="Dmitry Tsapik, Vladislav Suprun">
                <meta name="google-site-verification" content="G_cfwB-mweujBALcMpfubkpguMN8GMFLSAxIfzCrHEI" />                <meta name=\'yandex-verification\' content=\'4220f11129c2d620\' />
                <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
                <script src="/js/main.js"></script>
                <script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter8036914 = new Ya.Metrika({ id:8036914, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true, trackHash:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><!-- /Yandex.Metrika counter -->
                <title>' . $title . '</title>
              </head>';
        echo file_get_contents("content/header.html") .
             "\n<nav><ul class=\"nav\">" . $this->Menu_Read("content/menu.html") .
             "\n</ul></nav>\n<article>\n";
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
        echo "</article>\n<footer>\n" .
        file_get_contents("content/footer.html") .
        "</footer>" .
        $additional_content .
        "<script src=\"/js/menu.js\"></script>
        <div id=\"sticky\"></div>
        </body>
        </html>";
    }
}
?>