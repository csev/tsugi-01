<?php
require_once "db.php";
session_start();

requireLogin();

$tool = $_REQUEST['mod'];
if ( strlen($tool) < 1 ) $tool = $_REQUEST['tool'];

if ( ! isset($_GET['id']) ) {
    $_SESSION['err'] = 'Missing value for id';
    header( 'Location: error.php' ) ;
    return;
}

if ( strlen($tool) < 1 ) {
    $_SESSION['err'] = 'Missing value for mod/tool';
    header( 'Location: error.php' ) ;
    return;
}

$sql = "SELECT name,lkey,id FROM LTI_Courses WHERE id=? AND key_id=?"; 
$q = $db->prepare($sql);
$success = $q->execute(Array($_GET['id'],$CFG->localkeyid));
$course = $q->fetch();
$title = false;
if ( ! $course ) {
    $_SESSION['error'] = 'Bad value for id';
    header( 'Location: error.php' ) ;
    return;
}

debugClear();
if ( strlen($_REQUEST['tool']) > 0 ) {
    $location = 'launch.php?id='.$_GET['id'].'&tool='.$tool;
    doRedirect($location);
    return;
}
if ( strlen($course['name']) > 0 ) $title = $course['name'];
userMenu($title);
flashMessages();
?>
<iframe name="basicltiLaunchFrame"  id="basicltiLaunchFrame" 
  src="launch.php?id=<?php echo($_GET['id']); ?>&mod=<?php echo($_GET['mod']); ?>"
  width="100%" height="550" scrolling="auto" frameborder="1" transparency>
<p>frames_required</p>
</iframe>