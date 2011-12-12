<?php
require_once("../../config.php");
$context = moduleContext();
if ( ! $context->valid ) {
   die("Basic LTI Session failure ".$_SERVER['PHP_SELF']);
}

require_once("lib.php");

if ( $_POST['response'] ) {
    $responseData = $_POST['response'];
    postToWall($db,$responseData);
}

if ( $_FILES ) {
    addFileToPost($db, $_FILES);
}

if ( $_GET['deleteFile'] ) {
    $PostFileID = $_GET['deleteFile'];
    deletePostFile($db, $PostFileID);
}

$q = pdoRun($db, "SELECT * FROM Announcements JOIN LTI_Users ON Announcements.user_id=LTI_Users.id 
        ORDER BY Announcements.id DESC;");

$first = true;

headerContent();
flashMessages();

getPostFileList($db);
?>
<div id="announcement-feed">
<body style="font-family: century gothic;">
<center>
<form  method="post">
<textarea name="response" rows="10" cols="84">
</textarea><br/>
<input type="submit" value="Post an Announcement">
</form>
<form enctype="multipart/form-data" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
Choose a file to upload: <input name="uploadedFile" type="file" id="uploadedFile"/><br />
<input type="submit" value="Upload File" />
</form>
<p><table>
<tr><th width=600>Recent Announcements</th></tr>

<?php

while ( $q && $row = $q->fetch() ) {
    if ( $first ) {
        $first = false;
    }
    echo ("<tr><td>");
    echo ('<table style="border-style:outset; border-width:2px; border-color:white; padding:5px;"><tr><td width="30">');
    if ( strlen($row['image']) > 0 ) {
        echo('<img src="'.$row['image'].'" width="30" height="30" style="float:left">');
    }

    echo('</td><td width="570"><table width="570"><tr>');
    echo("<td><b>".$row['name']."</b> <i> ".$row['datetime']."</i></td></tr>");
    echo("<tr><td>".htmlentities($row['data'])."</td></tr>");
    echo('<tr><td><a href="index.php?reply?='.$row[0].'">Reply</a></td></tr></table>');
    echo("</td></tr></table>");
    echo("</td></tr>\n");
    
}

if ( $first ) {
    echo("<tr><td>No announcements found.</td></tr></table></center>\n");
} else {
    echo("</table></center>\n");
}
?>
</div>
<?php
footerContent();
