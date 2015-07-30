<?php
	include("index_part1.html");
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
		include("index_main.html");
	}
	include("index_part2.html");
?>