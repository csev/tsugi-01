<?php
require_once "db.php";
session_start();
requireLogin();

headerContent();
userMenu();
flashMessages();

$sql = sprintf("SELECT name, lkey, id FROM LTI_Courses WHERE key_id=%s",$CFG->localkeyid);
$q = $db->query($sql);
$first = true;

?>
<div id="medium-dialog-container">
<div id="medium-dialog">
<?php

$modules = getModules();
$module = false;
if ( count($modules) > 0 ) $module = $modules[0];
if (in_array("wall", $modules) ) $module = "wall";
while ( $q && $row = $q->fetch() ) {
    if ( $first ) {
        echo '<center><table>'."\n";
        echo("<tr><th>Course Name</th><th>Course Key</th><th>Tools</th></tr>\n");
        $first = false;
    }
    echo "<tr><td>";
    echo(htmlentities($row[0]));
    echo("</td><td>");
    echo(htmlentities($row[1]));
    echo("</td><td>\n");
    if ( $module === false ) {
        echo('No Modules Found.');
    } else {
        // echo('<a href="tool/'.$module.'/index.php">Launch</a> ');
        echo('(<a href="course.php?id='.htmlentities($row[2]).'&tool='.$module.'">Launch</a>) ');
        echo('(<a href="course.php?id='.htmlentities($row[2]).'&mod='.$module.'"
                    style="font-size: small; color: gray;">In Frame</a>) ');

    }
    echo("</td></tr>\n");
}

if ( $first ) {
    echo("<p>No courses found.</p>\n");
} else {
    echo("</table></center>\n");
}
?>
</div>
</div>
<?php
footerContent();