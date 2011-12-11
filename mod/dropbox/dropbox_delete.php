<?php
require_once("../../config.php");

// Get our session setup
$context = moduleContext();
if ( ! $context->valid ) {
   die("Session failure ".$_SERVER['PHP_SELF']);
}

if ( ! $context->isInstructor() ) {
    die("Only instructors can delete files");
}

$fn = $_REQUEST['file'];
if ( strlen($fn) < 1 ) {
    die("File name not found");
}

require_once "dropbox_util.php";
if ( isset($_POST["doDelete"]) ) {
    $foldername = getFolderName($context);
    $filename = $foldername . '/' . fixFileName($_POST['file']);
    if ( unlink($filename) ) { 
        $_SESSION['success'] = 'File deleted';
        $context->redirect('index.php');
    } else {
        $_SESSION['err'] = 'File delete failed';
        $context->redirect('index.php');
    }
    return;
}

// Switch to view / controller
headerContent();
flashMessages();

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
debugLog('Folder: '.$foldername);

footerContent();