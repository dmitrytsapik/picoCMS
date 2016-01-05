<?php
class MainPage extends Page_Template
{
    protected function do_events($dir_event, $total_news) {
    $files = $this->files_list($dir_event);
    $count_news=0;
    foreach($files as $event) { 
       if (!file_exists($dir_event . $event)) continue;
            $counter = 0;
            $handle = fopen($dir_event . $event, "r");
            if ($handle) {
                $count_news++;
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
                //ограничитель новостей
                if($count_news>=$total_news) break;
            }
            }
    }
    protected function do_popular() {
        $files = $this->files_list("content/popular/");
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
    protected function youtube() {
        if (file_exists("content/youtube") && filesize("content/youtube")) {
                echo "\n<div class=\"media\">\n";
                echo "\t<h2>Видеозаписи</h2>";
                $counter = 1;
                $handle = fopen("content/youtube", "r");
                if ($handle) {
                while (($buffer = fgets($handle)) !== false) {
                $buffer = "<iframe class=\"youtube\" src=\"http://www.youtube.com/embed/"
                             . preg_replace("/\s+/", "", $buffer) . 
                          "\" allowfullscreen></iframe>";
                if($counter % 2) {
                    echo "\n<div class=\"media_containers\">" . $buffer;
                    $trigger = 1;
                } else {
                    echo $buffer . "</div>\n";
                    $trigger = 0;
                }
                $counter++;
                }
                fclose($handle);
                if (isset($trigger)?$trigger:0) {
                    echo "</div>";
                } 
            }
                echo "</div>\n";
            }
    }
    /*protected function header_picture() {
        return "<div id=\"picture\">\n"
                . file_get_contents('content/header') .
               "</div>\n";
    }*/
    public function make() {
        $this->Header_Page(WEBSITE_TITLE);
        echo "<div id=\"picture\">\n"
                . file_get_contents('content/header');
                $loc_dir = array(array("annonce", "события"), array("news_deps", "новости"));
            foreach ($loc_dir as $category) {
                echo "\n<div class=\"" . $category[0] . "\">
                      \n<h2>" . $category[1] ."</h2>";
                      $this->do_events("content/". $category[0] ."/", 12); //12 новостей
                echo "</div>";
            }
        echo "</div>\n";
        //echo $this->header_picture();
        /*echo "<div class=\"events\">";
            //$loc_dir = array(array("annonce", "Анонс"), array("news", "Новости кафедр"), array("news_deps", "Объявления"));
            $loc_dir = array(array("annonce", "События"), array("news_deps", "Объявления"));
            foreach ($loc_dir as $category) {
                echo "\n<div class=\"" . $category[0] . "\">
                      \n<h2>" . $category[1] ."</h2>";
                      $this->do_events("content/". $category[0] ."/", 12); //12 новостей
                echo "</div>";
            }
            echo "</div>\n";*/
            /*echo "<h2>Новые статьи Физико-технического института</h2>
                    <div class=\"popular\">";
            $this->do_popular();
            echo "</div>";*/
        //$this->youtube();    
        $this->Footer_Page();
    }
}
?>