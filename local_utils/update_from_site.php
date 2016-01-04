<?php
class RESTClient {
  const USER_AGENT = 'tools-downloader';
}
$url = 'http://xn--e1adck2bibfh.xn--p1ai/tools/';
$username = "username";
$password = "password";
$b64 = base64_encode("$username:$password");
$auth = "Authorization: Basic $b64";
$opts = array (
        'http' => array (
            'method' => "GET",
            'header' => $auth,
            'user_agent' => RESTClient :: USER_AGENT,
        )
);
$context = stream_context_create($opts);
file_put_contents("master.zip", fopen("http://xn--e1adck2bibfh.xn--p1ai/tools/sync.php", 'rb', false, $context));
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
$zip = new ZipArchive;
if ($zip->open('master.zip') === TRUE) {
    $zip->extractTo('.');
    $zip->close();
    unlink('master.zip');
    $files = scandir("pages");
    foreach ($files as $file) {
      if($file == "." || $file == "..") continue;
      rrmdir("../pages/" . $file);
      rename("pages/" . $file, "../pages/" . $file);
    }
    if(file_exists("pages")) rrmdir("pages");
    echo 'We are done!!1';
} else {
    echo 'failed';
}
?>