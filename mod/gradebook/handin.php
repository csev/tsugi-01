<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>
Assignment Hand In
</title>
</head>

<?php
require_once "db.php";

if ( isset($_POST['userid']) && isset($_POST['wallid']) && isset($_POST['answer']) )
{
    $u = mysql_real_escape_string($_POST['userid']);
    $w = mysql_real_escape_string($_POST['wallid']);
    $a = mysql_real_escape_string($_POST['answer']);
    if( !empty($u) && !empty($w) && !empty($a) )
    {
        $sql = "INSERT INTO items(user_id,wall_id,answer) Values('$u','$w','$a')";
        mysql_query($sql);
    }
}
?>

<p>Item #</p>
<form method = "post">
<p>Student ID: <input type="text" name='userid'/></p>
<p>Wall ID: <input type="text" name='wallid'/></p>
<p>Enter your answer here: <p>
<p><textarea rows='10' cols='30' name='answer'></textarea></p>
<p><input type="submit" value="Hand In" /></p>
</form>