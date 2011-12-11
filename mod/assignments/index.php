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

headerContent();
flashMessages();
echo "<form method='post'>";
$q = pdoRun($db, "SELECT id,title,duedate FROM Assignments WHERE user_id=?", $_SESSION['user_id']);
$first = true;
while($row=$q->fetch())
{
    if ( $first ) {
        echo '<table>' . "\n";
        echo "<tr><th>Delete</hth><th>Assignment</th><th>Due Date</th></tr>";
        $first = false;
    }

    $id=$row[0];
    echo "<tr>";
    echo "<td><input type='checkbox' value='$id' name='task_id[]'/></td>";
    echo "<td>$row[1]</td>";
	echo "<td>$row[2]</td>";
    echo "</tr>\n";
}

if ( $first ) {
    echo("<p>No Assignments Found</p>\n");
} else {
    echo("</table>\n");
    echo('<input type="submit" value="Delete finished assignments" name="delete" id="delete"/></form>');
}

echo('<a href="add.php">Add New Assignment</a>');
footerContent();

