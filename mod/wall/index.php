<?php
require_once("../../config.php");
require_once $CFG->dirroot.'/lib/lti_util.php';

// Get our session setup without a cookie
session_start();
// We want this to come from the session, not from the secret - this is not launchable
$context = new LTI(rand()+"xyzzy", true, false);

if ( ! $context->valid ) {
   die("Basic LTI Session failure ".$_SERVER['PHP_SELF']);
}

require_once $CFG->dirroot.'/db.php';
require_once $CFG->dirroot.'/pdo_util.php';

setupPrimaryKeys($db, $context);

if ( $_POST['response'] ) {
        date_default_timezone_set('EST');
        $sql = sprintf("INSERT INTO Announcements (user_id, data, datetime) 
                        VALUES (%s, %s, %s)\n",
            $db->quote($_SESSION['user_id']),
            $db->quote($_POST['response']),
            $db->quote(date('M d, Y g:i a')) );
        // echo($sql);flush();
        $rows = $db->exec($sql);
        if ( $rows > 0 ) {
            $_SESSION['success'] = 'Data inserted';
        } else { 
            $_SESSION['err'] = 'Unable to insert data ';
        }
}

$sql = sprintf("SELECT * FROM Announcements JOIN LTI_Users ON Announcements.user_id=LTI_Users.id ORDER BY Announcements.id DESC;");
$q = $db->query($sql);
$first = true;

flashMessages();

?>
<div id="medium-dialog-container">
<div id="medium-dialog">
<center>
<form  method="post">
<textarea name="response" rows="10" cols="84">
</textarea><br/>
<input type="submit" value="Post an Announcement">
</form>
<p><table>
<tr><th width=600>Recent Announcements</th></tr>

<?php

while ( $q && $row = $q->fetch() ) {
    if ( $first ) {
        $first = false;
    }
    echo ("<tr><td>");
    echo ('<table style="background:white; border:0px;" class="announcement-feed-post"><tr><td width="30">');
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