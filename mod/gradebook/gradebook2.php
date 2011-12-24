<?php
require_once "db.php";
session_start();

if(isset($_SESSION['error']))
{
    echo '<p style="color:red">' . $_SESSION['error'] . "</p>\n";
    unset($_SESSION['error']);
}
if(isset($_SESSION['success']))
{
    echo '<p style="color:green">' . $_SESSION['success'] . "</p>\n";
    unset($_SESSION['success']);
}
echo '<table border="1">' . "\n";
echo "<th>item_id</th>";
echo "<th>grade</th>";
echo "<th>regrade</th>";
$result=mysql_query("SELECT item_id,grade,id FROM gradebook");
while($row=mysql_fetch_row($result))
{
    echo "<tr><td>";
    echo(htmlentities($row[0]));
    echo "</td><td>";
    echo(htmlentities($row[1]));
    echo "</td><td>\n";
    echo('<a href="regrade.php?id=' . htmlentities($row[2]) . ' ">Regrade</a>');
    echo "</td></tr>\n";
}
?>
</table>
<a href="gradebook.php">Grade More</a>