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
        $q = pdoRun($db, "DELETE FROM Assignments WHERE id=?", $id);
        if ( ! $q->success ) {
            $arr = $q->errorInfo();
            $_SESSION['err'] = 'Unable to delete task '.$arr[2];
        }
    }
    $context->redirect('index.php');
    return;
}
flashMessages();
echo "<form method='post'>";
echo '<table border="1">' . "\n";
$q = pdoRun($db, "SELECT id,title,duedate FROM Assignments WHERE user_id=?", $_SESSION['user_id']);
echo "<td><th>Assignment</th><th>Due Date</th></td>";
while($row=$q->fetch())
{
    $id=$row[0];
    echo "<tr>";
    echo "<td><input type='checkbox' value='$id' name='task_id[]'/></td>";
    echo "<td>$row[1]</td>";
	echo "<td>$row[2]</td>";
}
echo "</tr>\n";
?>
</table>
<input type='submit' value='Delete finished assignments' name='delete' id='delete'/></form>
<a href=add.php>Add New Assignment</a>

