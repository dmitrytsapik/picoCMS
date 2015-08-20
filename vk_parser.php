<?php
function post_parse($text, $date, $category) {
	$month = array("1" => "ЯНВ",
			   "2" => "ФЕВ",
			   "3" => "МАР",
			   "4" => "АПР",
			   "5" => "МАЙ",
			   "6" => "ИЮН",
			   "7" => "ИЮЛ",
			   "8" => "АВГ",
			   "9" => "СЕН",
			   "10" => "ОКТ",
			   "11" => "НОЯ",
			   "12" => "ДЕК");
	$post = $month[date("n", $date)] . "<br>" . date("j", $date) . "\n";
	$head = strstr($text, "<br>", true);
	if(strpos($head, " | ")) {
		$post .= "<a href=\"http://" . substr(strstr($head, ' | '), 3) . "\">" . strstr($head, ' | ', true) . "</a>";
	} else {
		$post .= "<a href=\"#\">" . $head . "</a>";
	}
	$post .= substr(strstr($text, "<br>"), 4);
	file_put_contents($category . "/" . $date,  $post);
}
sleep(3);
$url = "http://api.vk.com/method/wall.get?domain=phystech_managers&offset=0&count=1&filter=owner";
$response = file_get_contents($url);
$wall = json_decode($response);
$date = file_get_contents('parser');
if ($wall->response[1]->date!=$date) {
$url = "http://api.vk.com/method/wall.get?domain=phystech_managers&offset=0&count=10&filter=owner";
$response = file_get_contents($url);
$wall = json_decode($response);
unset($wall->response[0]);
foreach ($wall->response as $key) { 
	if ($key->post_type=="post" && $key->date>$date) {
		$text = $key->text;
		if(strpos($text, "#новости_кафедр_ФТИ")) {
			$text = str_replace("#новости_кафедр_ФТИ", "", $text);
			post_parse($text, $key->date, "content/news");
		} elseif(strpos($text, "#анонс_ФТИ")) {
			$text = str_replace("#анонс_ФТИ", "", $text);
			post_parse($text, $key->date, "content/annonce");
		} elseif(strpos($text, "#объявления_ФТИ")) {
			$text = str_replace("#объявления_ФТИ", "", $text);
			post_parse($text, $key->date, "content/news_deps");
		}

	}
	
}
file_put_contents("parser", $wall->response[1]->date);
}
$url = "http://api.vk.com/method/wall.get?domain=physic_studsovet&offset=0&count=1&filter=owner";
$response = file_get_contents($url);
$wall = json_decode($response);
$date = file_get_contents('parser_stud');
if ($wall->response[1]->date!=$date) {
$url = "http://api.vk.com/method/wall.get?domain=physic_studsovet&offset=0&count=10&filter=owner";
$response = file_get_contents($url);
$wall = json_decode($response);
unset($wall->response[0]);
foreach ($wall->response as $key) { 
	if ($key->post_type=="post" && $key->date>$date) {
		$text = $key->text;
		if(strpos($text, "#новости_кафедр_ФТИ")) {
			$text = str_replace("#новости_кафедр_ФТИ", "", $text);
			post_parse($text, $key->date, "content/news");
		} elseif(strpos($text, "#анонс_ФТИ")) {
			$text = str_replace("#анонс_ФТИ", "", $text);
			post_parse($text, $key->date, "content/annonce");
		} elseif(strpos($text, "#объявления_ФТИ")) {
			$text = str_replace("#объявления_ФТИ", "", $text);
			post_parse($text, $key->date, "content/news_deps");
		}

	}
	
}
file_put_contents("parser_stud", $wall->response[1]->date);
}
?>