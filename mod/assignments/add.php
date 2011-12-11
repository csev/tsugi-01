<?php
require_once("../../config.php");

// Get our session setup
$context = moduleContext();
if ( ! $context->valid ) {
   die("Session failure ".$_SERVER['PHP_SELF']);
}

// Model Section - Handle any input data
if ( isset($_POST['title']) )
{
    $q = pdoRun($db, "INSERT INTO Assignments (title, duedate, user_id) VALUES (?, ?, ?)",
        Array($_POST['title'], $_POST['duedate'], $_SESSION['user_id'])
    );
    if ( ! $q->success ) {
        $arr = $q->errorInfo();
        $_SESSION['err'] = 'Unable to add assignment '.$arr[2];
    }
    $context->redirect('index.php');
    return;
}

headerContent();
flashMessages();
?>
<form method="post">
<p>Assignment Title: <input type="text" name="title"/></p>
<p>Due Date: <input type="text" name="duedate"/></p>
<p>Please enter date as: "mm/dd/yyyy"</p>
<p><input type="submit" value="Add to Assignment List" /></p>
</form>
<?php
footerContent();