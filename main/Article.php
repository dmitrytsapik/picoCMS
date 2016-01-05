<?php
class ArticlePage extends Page_Template {
    public function replacer($text) {
        preg_match_all('/([A-Za-z0-9_\-]+\.)*[A-Za-z0-9_\-]+@([A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9]\.)+[A-Za-z]{2,4}/u',$text,$pEmail);
        foreach($pEmail[0] as $key => $val) {
            $email = filter_var($val, FILTER_VALIDATE_EMAIL);
            if($email) {
            $output[] = $email;
            }   
        }
        if (isset($output)) {
            foreach ($output as $key) {
            $text = str_replace("<a href=\"mailto:$key\">$key</a>", "<img style=\"padding: 0; vertical-align: middle;\" src=\"/?rend_mail=". strstr($key, '@', true) ."&rend_host_mail=" . substr(strstr($key, '@'), 1) ."\"><br>", $text);
            $text = str_replace($key, "<img style=\"padding: 0; vertical-align: middle;\" src=\"/?rend_mail=". strstr($key, '@', true) ."&rend_host_mail=" . substr(strstr($key, '@'), 1) ."\"><br>", $text);  
            }
        }
        return $text;
    }
    public function make($p_query) {
        $pages = $this->files_list("pages/");
        if (preg_match("/" . strtolower(implode('|', $pages)) . "/", $p_query)) {
            $q_file = str_replace($pages, "", $p_query);
            $q_main = str_replace($q_file,  "", $p_query);
            $menu = "pages/" . $q_main . "/menu.html";
            $page_out = "pages/" . $q_main . "/" . ((!$q_file)?"index.html" : substr($q_file, 1) . ".html");
            if(!file_exists($page_out)) $page_out = NULL;
        }
        if(isset($page_out)) {
            $buffer = file_get_contents($page_out);
            preg_match('{<h1[^>]*>(.*?)</h1>}', $buffer, $title_h1);
            preg_match('{<h2[^>]*>(.*?)</h2>}', $buffer, $title_h2);
            $this->Header_Page((isset($title_h2[1])? $title_h2[1] . " - " : "") . ((isset($title_h1[1]))? $title_h1[1] . " - " : "") . WEBSITE_TITLE_SHORT);
            echo "<div class=\"departments\">";
                if (file_exists($menu) && filesize($menu)>0) {
                    echo "<div class=\"menu\">".
                          file_get_contents($menu) .
                         "</div>";
                    }
                    echo "<div class=\"entry\">" .
                         $this->replacer($buffer) .
                         "</div>
                  </div>";
            $this->Footer_Page();
        } else {
            $c = new NotFound();
            $c->make(404);
            exit;
        }
    }
}
?>