<?php 
require_once("../../config.php");

// Get our session setup
$context = moduleContext();
if ( ! $context->valid ) {
   die("Session failure ".$_SERVER['PHP_SELF']);
}

// Switch to view / controller
headerContent();
flashMessages();
require_once "dropbox_util.php";

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