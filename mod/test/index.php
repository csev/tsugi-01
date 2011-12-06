<?php
require_once("../../config.php");

// Get our session setup
$context = moduleContext();
if ( ! $context->valid ) {
   die("Session failure ".$_SERVER['PHP_SELF']);
}

// Do any Model Processing (i.e. handling post data here )
if ( isset($_POST['add']) ) {
    $_SESSION['test_count'] ++;
}
if ( isset($_POST['reset']) ) {
    $_SESSION['test_count'] = 0;
}

// Switch to view / controller
headContent();
flashMessages();

?>
<p>Count: <?php echo($_SESSION['test_count']); ?></p>
<form method="post">
<input type="submit" name='add' value="Add One">
<input type="submit" name='reset' value="Reset Variable">
</form>
<h2>Debug Dump</h2>
<pre>
<?php
print "Context Information:\n\n";
print $context->dump();
print "\n\nSESSION\n";
print_r($_SESSION);
?>
</pre>
</body>
</html>

