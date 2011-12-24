<?php
require_once "db.php";

if ( isset($_POST['grade']) && isset($_POST['id']) )
{
    $g = mysql_real_escape_string($_POST['grade']);
    $id = mysql_real_escape_string($_POST['id']);
    $sql = "UPDATE gradebook SET grade='$g' WHERE id='$id'";
    mysql_query($sql);
    $_SESSION['success']='Grade updated';
    header( 'Location: gradebook2.php' );
    return;
}

$id=$_GET['id'];
$result=mysql_query("SELECT item_id,grade,id FROM gradebook WHERE id='$id'");
$row=mysql_fetch_row($result);
if($row===FALSE)
{
    $_SESSION['error']='Bad value for id';
    header( 'Location: gradebook2.php' );
    return;
}
$i=htmlentities($row[0]);
$g=htmlentities($row[1]);
$id=htmlentities($row[2]);
echo <<<_END
<p>Regrade</p>
<form method="post">
<p>Grade: <input type="text" name="grade" value="$g"></p>
<p><input type="hidden" name="id" value="$id"></p>
<p><input type="submit" value="Regrade" /><a href="gradebook2.php">Cancel</a></p>
</form>
_END
?>