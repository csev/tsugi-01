<?php
require_once("../../config.php");

// Get our context setup
$context = moduleContext();
if ( ! $context->valid ) {
   die("Session failure ".$_SERVER['PHP_SELF']);
}

// Model Section - Handle any input data
if ( isset($_REQUEST['cancel']) ) $context->redirect('index.php');

if ( ! $context->isInstructor() ) {
  die("You must be an instrcutor to grade - doh!");
}

if ( !isset($_REQUEST['response_id']) ) die("Missing required parameter");

// Security Note:
// This will only work if we are grading the response for the resource associated
// With this context/launch/ etc - We take the ResourceID from the context

$q = pdoRun($db,
    "SELECT * FROM Responses JOIN LTI_Users JOIN LTI_Resources 
    ON Responses.user_id = LTI_Users.id AND Responses.resource_id = LTI_Resources.id
    WHERE resource_id=? AND Responses.id=?",
    Array($context->getResourceID(), $_REQUEST['response_id'])
);
$response = $q->fetch();
if ( ! $response ) die("Response not found");
// print "\n<pre>\n"; print_r($response); print "\n</pre>\n";flush();

if ( $_POST['grade'] || $_POST['note'] ) {
    if ($_POST['grade'] != $response['grade'] || $_POST['note'] != $response['note'] ) {
        $q = pdoRun($db,
            "UPDATE Responses SET note=?, grade=? WHERE id=?",
            Array($_POST['note'], $_POST['grade'],$_REQUEST['response_id'])
        );
        if ($q->success) $rows = $q->rowCount();
        if ( $rows < 1 ) {
            $_SESSION['err'] = 'Unable to update Grade';
            $context->redirect('index.php');
            return;
        }
        $_SESSION['success'] = 'Grade Stored';
	}

    if ( strlen($response['sourcedid']) == 0 || strlen($response['service']) == 0 ) { 
        $context->redirect('index.php');
        return;
    }

    // Time to send the grade...
    $operation = 'replaceResultRequest';
    $postBody = str_replace(
        array('SOURCEDID', 'GRADE', 'OPERATION','MESSAGE'), 
        array($response['sourcedid'], $_POST['grade'], $operation, uniqid()), 
        getPOXGradeRequest());

    $oauth_consumer_key = '12345';
    $oauth_consumer_secret = 'secret';
    $content_type = 'application/xml';
    $endpoint = $response['service'];

    $response = sendOAuthBodyPOST("POST", $endpoint, $oauth_consumer_key, $oauth_consumer_secret, $content_type, $postBody);
    $retval = parseResponse($response);

    if ( $retval['imsx_codeMajor'] == 'success' ) {
        $_SESSION['success'] = 'Grade Uploaded to LMS';
    } else {
        $_SESSION['err'] = 'Grade stored, but upload failed: '.$retval['imsx_description'];
    }

    echo("<p>Grade sent...</p>\n");
    echo('<p><a href="index.php">Continue...</a></p>');
    echo("\n<pre>\n");
    echo("Service Url:\n");
    echo(htmlentities($endpoint)."\n\n");
    print_r($retval);
    echo("\n");
    echo("------------ POST RETURNS ------------\n");
    $response = str_replace("><","&gt;\n&lt;",$response);
    $response = str_replace("<","&lt;",$response);
    $response = str_replace(">","&gt;",$response);
    echo($response);
    
    echo("\n\n------------ WE SENT ------------\n");
    $postBody = str_replace("<","&lt;",$postBody);
    $postBody = str_replace(">","&gt;",$postBody);
    echo($postBody);
    echo("\n</pre>\n");
    return;
}

// Switch to view / controller
headerContent();
flashMessages();
?>
<form  method="post">
<div style="float:right">
<input type="text" name="grade" size="5" value="<?php echo(htmlentities($response['grade'])); ?>">
<input type="submit" value="Grade">
<input type="submit" name="cancel" value="Cancel"><br/>
Notes:<br/>
<textarea name="note" rows="10" cols="20">
<?php echo(htmlentities($response['note'])); ?>
</textarea><br/>
</div>
<p>
<?php 
if ( strlen($response['image']) > 0 ) {
    echo('<img src="'.htmlentities($response['image']).'" width="30" height="30">');
}
echo(htmlentities($response['name']));
echo("</p>\n<p>");
echo(htmlentities($response['data']));
echo("</p>\n");
echo("</form>\n");
echo('<br clear="all"/>');

footerContent();
