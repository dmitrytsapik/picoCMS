<?php
    if (isset($_GET['vk_parser'])) {
        include 'main/vk_parser.php';
        exit;
    }
    include 'main/config.php';
    include 'main/Template.php';
    include 'main/Article.php';
    include 'main/Main.php';
    include 'main/NotFound.php';
    if (isset($_GET['rend_mail']) && isset($_GET['rend_host_mail'])) {
        $string = $_GET['rend_mail'] . "@" . $_GET['rend_host_mail'];
        require_once 'main/Email2Image.php';
        $email2Image = new Email2Image();
        $email2Image->setFontPath('./fonts/');
        $email2Image->setFontFile('tahoma.ttf');
        $email2Image->setFontSize(14);
        $email2Image->setWidth(strlen($string) * 14);
        $email2Image->setHeight(25);
        $email2Image->setBackgroundColor('FFFFFF');
        $email2Image->setForegroundColor('444444');
        $email2Image->setHorizontalAlignment(Email2Image::LEFT);
        $email2Image->setVerticalAlignment(Email2Image::MIDDLE);
        $email2Image->setEmail($string);
        $email2Image->outputImage();
        exit;
    }
    if(isset($_GET['error'])? $_GET["error"] : '') {
         $c = new NotFound();
         $c->make($_GET['error']);
         exit;
     }
    if((isset($_GET["timetable"]) ? $_GET["timetable"] : '')) {
        include 'main/TimeTable.php';
        $b = new TimeTable();
        $b->make();
        exit;
    }
    if((isset($_GET["calendar"]) ? $_GET["calendar"] : '')) {
        include 'main/Calendar.php';
        $b = new Calendar();
        $b->make();
        exit;
    } 
    if((isset($_GET["p"]) ? $_GET["p"] : '')) {
        $b = new ArticlePage();
        $b->make($_GET["p"]);
	}     
	 else
	 {
	 	$a = new MainPage();
        $a->make();
	 }
?>