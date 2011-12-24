<?php
require_once("../../config.php");

// Get our session setup
$context = moduleContext();
if ( ! $context->valid ) {
   die("Session failure ".$_SERVER['PHP_SELF']);
}

$fn = $_REQUEST['file'];
if ( strlen($fn) < 1 ) {
    die("File name not found");
}

require_once "dropbox_util.php";
$fn = fixFileName($fn);
$foldername = getFolderName($context);
$filename = $foldername . '/' . fixFileName($fn);

if ( ! file_exists($filename) ) {
   die("File does not exist");
}

// Get the mime type
$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
$mimetype = finfo_file($finfo, $filename);
finfo_close($finfo);

// die($mimetype);

if ( strlen($mimetype) > 0 ) header('Content-Type: '.$mimetype );
header('Content-Disposition: attachment; filename="'.$fn.'"');

echo readfile($filename);