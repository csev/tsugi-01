<?php
require_once "db.php";
session_start();

//insert data into gradebook
if( !empty($_POST['id']) && !empty($_POST['grade']) )
{
    $id=$_POST['id'];
    $g=$_POST['grade'];
    mysql_query("INSERT INTO gradebook (item_id,grade) VALUES('$id','$g')");
    $_SESSION['success'] = 'Graded Successfully';
    header( 'Location: gradebook2.php' );
    return;
}

// sending query
$result = mysql_query("SELECT * FROM items WHERE NOT EXISTS (SELECT * FROM gradebook WHERE gradebook.item_id=items.id)");
if (!$result) 
{
    die("Query to show fields from table failed");
}

$fields_num = mysql_num_fields($result);

// printing table headers
echo "\n";
echo '<table border="1">' . "\n";
echo "<th>ItemID</th>";
echo "<th>StudentID</th>";
echo "<th>WallID</th>";
echo "<th>Answer</th>";
echo "<th>Grade</th>";
echo "</tr>\n";
// printing table rows
while($row = mysql_fetch_row($result))
{
    $id = $row[0];
    echo "<tr>";
    foreach($row as $cell)
        echo "<td>$cell</td>";
    echo "<td><form method='post'><input type='text' name='grade'/>";
    echo "<input type='hidden' value='$id' name='id' />";
    echo "<input type='submit' value='submit' name='submit' /></form></td>";
    echo "</tr>\n";
}
mysql_free_result($result);
echo "</table>";


?>