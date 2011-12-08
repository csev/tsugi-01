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
        $sql = "DELETE FROM Tasks WHERE id=?";
        $q = $db->prepare($sql);
        $success = $q->execute(Array($id));
        if ( ! $success ) {
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
$uid=$_SESSION['user_id'];

$sql = "SELECT id,title FROM Tasks WHERE user_id=?";
$q = $db->prepare($sql);
$success = $q->execute(Array($uid));
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
<input type='submit' value='Delete finished tasks' name='delete' id='delete'/></form>
<a href=add.php>Add New Task</a>

