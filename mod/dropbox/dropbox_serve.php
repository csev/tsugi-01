<?php
require_once 'ims-blti/blti.php';
require_once 'dropbox_util.php';

// Get out session setup without a cookie
$context = establishContext();

if ( ! $context->valid ) {
    die("Basic LTI Session Failure");
}

$fn = $_REQUEST['file'];
if ( strlen($fn) < 1 ) {
    die("File name not found");
}

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