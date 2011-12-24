<?php
require_once("../../config.php");
require_once("datesArray.php");

$array_in = make_date(7);
$dates_size = count($array_in);

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

echo '<table>' . "\n";
$q = pdoRun($db, "SELECT id,title,duedate FROM Assignments WHERE user_id=?", $_SESSION['user_id']);

echo "<table>";
echo "\n<tr>";

$k = 0;		
while ($k<$dates_size) {
	echo "\n<th>$array_in[$k]</th>";
	$k = $k+1;
}
	
echo "\n</tr>";

//Date information
echo "\n<tr>";

$due_arr = array();
$title_arr = array();

while($row=$q->fetch()) {
	$due_arr[] = $row[2];
	$title_arr[] = $row[1];
}

$db_arr_size = count($title_arr);
$k = 0;
while ($k<$dates_size) {
	$dates_entered = False;
	$j = 0;
	echo "\n<td>";
	while($j<$db_arr_size) {	
		if ($due_arr[$j]==$array_in[$k]) {
			echo "$title_arr[$j]<br>";
			$dates_entered = True;
		}
		$j = $j+1;
	}
	if ($dates_entered == False) {
		echo "Empty";
	}
	echo "</td>";
	$k = $k+1;
}
		
echo "\n</tr>";
echo "\n</table>";
	
footerContent();
