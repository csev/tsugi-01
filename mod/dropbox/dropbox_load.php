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

$uploaded=0;
$ext="";

if((!empty($_FILES["uploaded_file"])) && ($_FILES['uploaded_file']['error'] == 0))
{
   $filename =strtolower(basename($_FILES['uploaded_file']['name']));

   $filename = fixFileName($filename);

   if ($_FILES["uploaded_file"]["size"] < 6000000)
   {
       $foldername = getFolderName($context);
       $ext=".".$ext;
       $newname = $foldername.'/'.$filename;
       if ((move_uploaded_file($_FILES['uploaded_file']['tmp_name'],$newname)))
       {
           echo "File uploaded successfully!";
           $uploaded=1;
       }
       else
       {
           echo "Error:!";
           print('<p><a href="dropbox.php?">Back</a></p>');
       }
    } else {
       echo "Error: This only accepts files 5GB or smaller.";
       print('<p><a href="upload_file.php">Back</a></p>');
    }
} else {
    echo "Error! File is not uploaded!";
    print('<p><a href="upload_file.php">Back</a></p>');
}

?> 

<p><input type="submit" name="doDone" onclick="location='dropbox.php'; return false;" value="Continue"></p>