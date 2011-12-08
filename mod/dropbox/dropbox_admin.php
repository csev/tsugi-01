<h4>DropBox Admin</h4>
<?php
require_once 'dropbox_util.php';

if ( $SALT == 'secret' ) {
    echo "All secrets are 'secret'";
    return;
}

if ( isset($_POST['key']) && isset($_POST['salt']) && $SALT == $_POST['salt'] ) {
    echo "Key: <b>" . $_POST['key'] . "</b><br/>\nSecret: <b>" . getSecret($_POST['key']) . "</b>\n";
}
?>
<form name=myform enctype="multipart/form-data" method="post">
<p>Key: <input type="text" name="key"></p>
<p>Salt: <input type="text" name="salt"></p>
<p><input type="submit" value="Generate Key"></p>
</form>