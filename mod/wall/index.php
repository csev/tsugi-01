<?php
require_once("../../config.php");
$context = moduleContext();
if ( ! $context->valid ) {
   die("Basic LTI Session failure ".$_SERVER['PHP_SELF']);
}

if ( $_POST['response'] ) {
    date_default_timezone_set('EST');
    $responseData = $_POST['response'];
    postToWall($db,$responseData);
}

if ( $_FILES ) {
    $target_path = $CFG->wwwroot;
    $target_path = $target_path . "/" . basename( $_FILES['uploadedFile']['name']);
    echo "Upload: " . $_FILES["uploadedFile"]["name"] . "<br />";
    echo "Type: " . $_FILES["uploadedFile"]["type"] . "<br />";
    echo "Size: " . ($_FILES["uploadedFile"]["size"] / 1024) . " Kb<br />";
    echo "Stored in: " . $_FILES["uploadedFile"]["tmp_name"] . "<br />";
    echo $target_path . "<br />";

    if (move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $target_path) ) {
        $_SESSION['success'] = "The file ".  basename( $_FILES['uploadedFile']['name']). 
        " has been uploaded";
    } else{
        $_SESSION['err'] = "Unable to insert data -- Error Code " .$_FILES['uploadedFile']['error'];
    }
}

$sql = "SELECT * FROM Announcements JOIN LTI_Users ON Announcements.user_id=LTI_Users.id ORDER BY Announcements.id DESC;";
$q = $db->prepare($sql);
$q->execute();

$first = true;

flashMessages();

?>

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
<p><table border=1>
<tr><th width=600>Recent Announcements</th></tr>

<?php

while ( $q && $row = $q->fetch() ) {
    if ( $first ) {
        $first = false;
    }
    echo ("<tr><td>");
    echo ('<table class="announcement-feed-post"><tr><td width="30">');
    if ( strlen($row['image']) > 0 ) {
        echo('<img src="'.$row['image'].'" width="30" height="30" style="float:left">');
    }

    echo('</td><td width="570"><table width="570"><tr>');
    echo("<td>".$row['name']." ".$row['datetime']."</td></tr>");
    echo("<tr><td>".htmlentities($row['data'])."</td></tr>");
    echo('<tr><td><a href="wall.php?reply?='.$row[0].'">Reply</a></td></tr></table>');
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
</div>
</body>