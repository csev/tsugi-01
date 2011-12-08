<?php 
// Load up the Basic LTI Support code
require_once 'ims-blti/blti.php';
require_once 'dropbox_util.php';

error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
header('Content-Type: text/html; charset=utf-8'); 

session_start();
session_regenerate_id();
// Initialize, all secrets are 'secret', do not set session, and do not redirect
$context = new BLTI(getSecret(), true, false);
$sessid = session_id();

if ( ! $context->valid ) {
    echo("<p>This tool must be launched using IMS LTI from a Learning Management System.");
    if ( $SALT == 'secret' ) {
	echo("  All secrets are 'secret'.</p>\n");
    } else {
        echo(" Send your desired key to the site owner to get your secret.\n");
    }
    return;
}

?>
<html>
<head><title>
<?php echo $context->getCourseName; echo " "; echo $context->getResourceTitle(); ?>
</title> 
<?php
foreach ( $context->getCSS() as $css ) {
    echo '<link rel="stylesheet" type="text/css" href="'.$css.'" />'."\n";
}
?>
</head> 
<body>
<?php

$foldername = getFolderName($context);
if ( !file_exists($foldername) ) mkdir ( $foldername);
// if ( !file_exists($foldername.'-students') ) mkdir ( $foldername.'-students');

echo "<h4>" . $context->getCourseName() . " " . $context->getResourceTitle() . " DropBox<h4>\n<ul>\n";
$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
$count = 0;
foreach (glob($foldername."/*") as $filename) {
    $fn = substr($filename,strlen($foldername)+1);
    echo '<li><a href="dropbox_serve.php?file='.$fn.'&xsession='. session_id() .'" target="_new">'.$fn.'</a>';
    if ( $context->isInstructor() ) {
    	echo ' (<a href="dropbox_delete?file='.$fn.'&xsession='. session_id() .'">Delete</a>)';
    }
    echo '</li>';
    $count = $count + 1;
    // echo $filename . " " . finfo_file($finfo, $filename) . "\n";
}
if ( $count == 0 ) echo "<p>No Files Found</p>\n";

echo("</ul>\n");
finfo_close($finfo);

if ( $context->isInstructor() ) { ?>
<h4>Upload new file</h4>
<form name=myform enctype="multipart/form-data" method="post" action="dropbox_load.php">
<p><input type=hidden name=xsession value="<?php echo session_id(); ?>"></p>
<p>Upload File: <input name="uploaded_file" type="file"> 
   <input type="submit" name="submit" value="Upload"></p>
</form>
<?php
}

print "<!-- \n";
echo ($foldername."\n");
echo (getStudentFolder($context)."\n");



print "Context Information:\n\n";
print $context->dump();
print "-->\n";

?>
<body>
</html>