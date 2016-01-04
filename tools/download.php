<?php
function rrmdir($dir) {
   if (is_dir($dir)) {
     $objects = scandir($dir);
     foreach ($objects as $object) {
       if ($object != "." && $object != "..") {
         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
       }
     }
     reset($objects);
     rmdir($dir);
   }
} 
file_put_contents("master.zip", fopen("https://github.com/PhysTechCFU/pages/archive/master.zip", 'rb'));
$zip = new ZipArchive;
if ($zip->open('master.zip') === TRUE) {
    $zip->extractTo('.');
    $zip->close();
    unlink('master.zip');
    if(file_exists("../pages")) rrmdir("../pages");
    rename("pages-master", "../pages");
    echo 'We are done!!1';
} else {
    echo 'failed';
}
//rmdir('test/website-master/');
?>