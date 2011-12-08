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
        mysql_query("DELETE FROM tasks WHERE id='$id'");
    }
    $_SESSION['success'] = 'Delete finished tasks';
    header( 'Location: index.php' );
    return;
}
echo "<form method='post'>";
echo '<table border="1">' . "\n";
$uid=$_SESSION['user_id'];
$result=mysql_query("SELECT id,title FROM tasks where user_id='$uid'");

while($row=mysql_fetch_row($result))
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

