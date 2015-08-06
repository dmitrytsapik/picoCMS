<?php
class ArticlePage extends Page_Template {
    public function make($p_query) {
        $this->Header_Page();
        $pages = $this->files_list("pages/");
        foreach ($pages as $word) {
        if (preg_match("/" . strtolower($word) . "/", $p_query)) {
            $q_file = str_replace($word . "-", "", $p_query);
            if ($word==$q_file) {
                if (file_exists("pages/" . $word . "/index.html")) {
                    echo "<div class=\"departments\">"."<div style=\"padding-right: 2em;\">".
                         file_get_contents("pages/" . $word . "/menu.html") .
                         "</div>" .
                         "<div class=\"block2\" style=\"min-width: 700px\">" .
                         file_get_contents("pages/" . $word . "/index.html") .
                         "</div></div>";
                    break;
                } else {
                    echo "<h1>Запрос введен неверно</h1>";
                }
                } else {
                if (file_exists("pages/" . $word . "/" . $q_file . ".html")) {
                    echo "<div class=\"departments\">"."<div style=\"padding-right: 2em;\">".
                         file_get_contents("pages/" . $word . "/menu.html") .
                         "</div>" .
                         "<div class=\"block2\" style=\"min-width: 700px\">" .
                         file_get_contents("pages/" . $word . "/" . $q_file . ".html") .
                         "</div></div>";
                    break;
                } else {    
                    echo "<h1>Запрос введен неверно</h1>";
                }

            }
        }
        }
    $this->Footer_Page();
    }
}
?>