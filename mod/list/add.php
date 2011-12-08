<?php
require_once("../../config.php");

// Get our context setup
$context = moduleContext();
if ( ! $context->valid ) {
   die("Session failure ".$_SERVER['PHP_SELF']);
}

// Model Section - Handle any input data

if ( isset($_POST['title']) )
{
    $t = mysql_real_escape_string($_POST['title']);
    if( !empty($t) )
    {
        $uid=$_SESSION['user_id'];
        $sql = "INSERT INTO tasks(title) Values('$t') WHERE user_id='$uid'";
        mysql_query($sql);
        $_SESSION['success'] = 'Task Added';
        header( 'Location: index.php' );
        return;
    }
}
?>

<form method = "post">
<p>Task Title: <input type="text" name='title'/></p>
<p><input type="submit" value="Add to TO-DO List" /></p>
</form>