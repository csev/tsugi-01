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
    $t = $_POST['title'];
    $uid=$_SESSION['user_id'];
    $sql = "INSERT INTO Tasks (title, user_id) VALUES (?, ?)";
    $q = $db->prepare($sql);
    $success = $q->execute(Array($t, $uid));
    if ( ! $success ) {
        $arr = $q->errorInfo();
        $_SESSION['err'] = 'Unable to add task '.$arr[2];
    }
    $context->redirect('index.php');
    return;
}
?>

<form method="post">
<p>Task Title: <input type="text" name="title"/></p>
<p><input type="submit" value="Add to TO-DO List" /></p>
</form>