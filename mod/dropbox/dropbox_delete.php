<?php

require_once 'ims-blti/blti.php';
require_once 'dropbox_util.php';

// Get out session setup without a cookie
$context = establishContext();

if ( ! $context->valid ) {
   die("Basic LTI Session failure");
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

if ( ! $context->isInstructor() ) {
    die("Only instructors can delete files");
}

$fn = $_REQUEST['file'];
if ( strlen($fn) < 1 ) {
    die("File name not found");
}

if ( isset($_POST["doDelete"]) ) {
   $foldername = getFolderName($context);
   $filename = $foldername . '/' . fixFileName($_POST['file']);
   if ( unlink($filename) ) { 
        echo("<h4>File Deleted...</h4>\n");
   }
?> <p><input type="submit" name="doDone" onclick="location='dropbox.php'; return false;" value="Continue"></p>  <?php
    return;
}

echo '<h4 style="color:red">Are you sure you want to delete: ' .$fn. "</h4>\n"; 
?>
<form name=myform enctype="multipart/form-data" method="post" action="dropbox_delete.php">
<p>
    <input type=hidden name="xsession" value="<?php echo session_id(); ?>">
    <input type=hidden name="file" value="<?php echo $_REQUEST['file']; ?>">

</p>
<p><input type=submit name=doCancel onclick="location='dropbox.php'; return false;" value="Cancel">
<input type=submit name=doDelete value="Delete"></p>
</form>
<?php

?>