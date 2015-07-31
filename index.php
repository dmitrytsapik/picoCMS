<?php
	function do_events($dir_event) {
        $files = scandir($dir_event);
            unset($files[0], $files[1]);
            asort($files);
            $files = array_values($files);
            for ($i=$files[count($files)-1]; $i >= 0; $i--) { 
                if (!file_exists($dir_event . $i)) continue;
                $counter = 0;
                $handle = fopen($dir_event . $i, "r");
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
		include("main/main.html");
	}
    echo "      </article>\n";
	include("main/footer.html");
?>