<?php 
// die("ASLKSJKALKJASJLKSALKJSAJLK");
require_once("../../config.php");

// Get our session setup
$context = moduleContext();
if ( ! $context->valid ) {
   die("Session failure ".$_SERVER['PHP_SELF']);
}

require_once "dropbox_util.php";
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
            $_SESSION['success'] = 'File uploaded';
            $context->redirect('index.php');
       }
       else
       {
            $_SESSION['err'] = 'File upload failed';
            $context->redirect('index.php');
       }
    } else {
        $_SESSION['err'] = 'Error: This only accepts files 5GB or smaller.';
        $context->redirect('index.php');
    }
    return;
}

// Switch to view / controller
headerContent();
flashMessages();
$foldername = getFolderName($context);
debugLog($foldername);
if ( !file_exists($foldername) ) mkdir ($foldername);
// if ( !file_exists($foldername.'-students') ) mkdir ( $foldername.'-students');

$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
$count = 0;
foreach (glob($foldername."/*") as $filename) {
    $fn = substr($filename,strlen($foldername)+1);
    echo '<li><a href="dropbox_serve.php?file='.$fn.'" target="_new">'.$fn.'</a>';
    if ( $context->isInstructor() ) {
    	echo ' (<a href="dropbox_delete.php?file='.$fn.'">Delete</a>)';
    }
    echo '</li>';
    $count = $count + 1;
    debugLog($filename . " " . finfo_file($finfo, $filename));
}
if ( $count == 0 ) echo "<p>No Files Found</p>\n";

echo("</ul>\n");
finfo_close($finfo);

if ( $context->isInstructor() ) { ?>
<h4>Upload new file</h4>
<form name="myform" enctype="multipart/form-data" method="post" action="index.php">
<p>Upload File: <input name="uploaded_file" type="file"> 
   <input type="submit" name="submit" value="Upload"></p>
</form>
<?php
}

debugLog('Folder: '.$foldername);
debugLog('Student Folder: '.getStudentFolder($context));
debugLog("\nContext Information:");
debugLog($context->dump());

footerContent();