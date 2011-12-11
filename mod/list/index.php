<?php
require_once("../../config.php");

// Get our session setup
$context = moduleContext();
if ( ! $context->valid ) {
   die("Session failure ".$_SERVER['PHP_SELF']);
}

// Do any Model Processing (i.e. handling post data here )
if(!empty($_POST['task_id']) )
{
    $ids=$_POST['task_id'];
    foreach($ids as $id)
    {
        $q = pdoRun($db, "DELETE FROM Tasks WHERE id=?", $id);
        if ( ! $q->success ) {
            $arr = $q->errorInfo();
            $_SESSION['err'] = 'Unable to delete task '.$arr[2];
        }
    }
    $context->redirect('index.php');
    return;
}

// Start of controller/view
headerContent();
flashMessages();
echo "<form method='post'>";
echo '<table border="0" style="border-style:outset; border-width:2px; border-color:white;" >' . "\n";
$q = pdoRun($db, "SELECT id,title FROM Tasks WHERE user_id=?", $_SESSION['user_id']);
while($row=$q->fetch())
{
    $id=$row[0];
    echo "<tr>";
    echo "<td><input type='checkbox' value='$id' name='task_id[]'/></td>";
    echo "<td>$row[1]</td>";
}
echo "</tr>\n";
?>
</table>
<br/>
<input type='submit' value='Delete finished tasks' name='delete' id='delete'/></form>
<a href=add.php>Add New Task</a>
<?php
footerContent();

