<?php
	function do_events($dir_event) {
        $files = scandir($dir_event);
            unset($files[0], $files[1]);
            arsort($files);
            $files = array_values($files);
            foreach($files as $event) { 
                if (!file_exists($dir_event . $event)) continue;
                $counter = 0;
                $handle = fopen($dir_event . $event, "r");
                if ($handle) {
                echo "<div class=\"event\">\n";
                while (($buffer = fgets($handle)) !== false) {
                switch ($counter) {
                    case 0:
                        echo "<div>" . $buffer . "</div>\n";
                        break;
                    case 1:
                        echo "<div class=\"event-text\">\n" . $buffer;
                        break;
                    case 2:
                        echo "<p>" . $buffer . "</p>";
                        break;
                }
                $counter++;
                }
                /*if (!feof($handle)) {
                echo "Error: unexpected fgets() fail\n";
                }*/
                fclose($handle);
                echo "</div>\n</div>\n";
            }
            }
    }
    function do_popular() {
        $files = scandir("content/popular/");
        unset($files[0], $files[1]);
        arsort($files);
        $files = array_values($files);
        foreach($files as $event) { 
            if (!file_exists("content/popular/" . $event)) continue;
                $counter = 0;
                $handle = fopen("content/popular/" . $event, "r");
                if ($handle) {
                while (($buffer = fgets($handle)) !== false) {
                $buffer_short = substr($buffer, 0, -2);
                switch ($counter) {
                    case 0:
                        echo "\n<a href=\"" . $buffer_short . "\">";
                        break;
                    case 1:
                        echo "\n<img src=\"" . $buffer_short . "\"";
                        break;
                    case 2:
                        echo " alt=\"" . $buffer_short . "\">";
                        break;
                    case 3:
                        echo "\n<h4>" . $buffer_short . "</h4>";
                        break;
                    case 4:
                        echo "\n" . $buffer . "\n</a>";
                        break;
                }
                $counter++;
                }
                fclose($handle);
            }
            }
    }
    include "main/header.html";
    echo "\n";
    include "main/menu.html";
    echo "\n   <article>\n";
    if((isset($_GET["p"]) ? $_GET["p"] : '')) {
        $p_query = $_GET["p"];
        $files = scandir("pages/");
        unset($files[0], $files[1]);
        $pages = array();
        foreach($files as $file) {
            array_push($pages, $file);
        }
        foreach ($pages as $word) {
        if (preg_match("/" . strtolower($word) . "/", $p_query)) {
            $q_file = str_replace($word . "-", "", $p_query);
            if ($word==$q_file) {
                if (file_exists("pages/" . $word . "/index.html")) {
                    include("pages/" . $word . "/menu.html");
                    include("pages/" . $word . "/index.html");
                    break;
                } else {
                    echo "<h1>Запрос введен неверно</h1>";
                }
                } else {
                if (file_exists("pages/" . $word . "/" . $q_file . ".html")) {
                    include("pages/" . $word . "/menu.html");
                    include("pages/" . $word . "/" . $q_file . ".html");
                    break;
                } else {    
                    echo "<h1>Запрос введен неверно</h1>";
                }

            }
        }
        }
	} 
	else
	{
		include("main/default_header.html");
        echo "<div class=\"events\">";
            $loc_dir = array(array("annonce", "Анонс"), array("news", "Новости кафедр"), array("news_deps", "Объявления"));
            foreach ($loc_dir as $category) {
                echo "\n<div class=\"" . $category[0] . "\">
                      \n<h2>" . $category[1] ."</h2>";
                      do_events("content/". $category[0] ."/");
                echo "</div>";
            }
            echo "</div>\n";
            echo "<h2>Новые статьи Физико-технического института</h2>
                    <div class=\"popular\">";
            do_popular();
            echo "</div>";
        include("main/default_footer.html");
	}
       echo "      </article>\n";
	include("main/footer.html");
?>