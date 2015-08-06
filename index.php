<?php
    include 'main/Page_Template.php';
    include 'main/ArticlePage.php';
    include 'main/MainPage.php';
    include 'main/NotFound.php';
    if(isset($_GET['error'])? $_GET["error"] : '') {
         $c = new NotFound();
         $c->make($_GET['error']);
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