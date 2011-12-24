<?php
require_once("../../config.php");

// Get our session setup
$context = moduleContext();
if ( ! $context->valid ) {
   die("Session failure ".$_SERVER['PHP_SELF']);
}

if ( $_POST['response'] ) {
    $q = pdoRun($db, 
        "SELECT * FROM Responses WHERE resource_id=? AND user_id=? LIMIT 1", 
        Array($context->getResourceID(), $context->getUserID()) );
    $response = $q->fetch();
    if ( $response ) {

        // Do we need to change anything?
        if ( $response['data'] != $_POST['response'] || 
           ( strlen($context->getOutcomeSourceDID()) > 0 &&
              $response['sourcedid'] != $context->getOutcomeSourceDID() ) ) {

            if ( strlen($context->getOutcomeSourceDID()) > 0 ) {
                $q = pdoRun($db,
                    "UPDATE Responses SET data=?, sourcedid=? WHERE resource_id=? AND user_id=?",
                    Array($_POST['response'], $context->getOutcomeSourceDID(),
                            $context->getResourceID(), $context->getUserID()) 
                );
            } else {
                $q = pdoRun($db,"UPDATE Responses SET data=? WHERE resource_id=? AND user_id=?",
                    Array($_POST['response'], $context->getResourceID(), $context->getUserID()) 
                );
            }
            if ($q->success) $rows = $q->rowCount();
            if ( $rows > 0 ) {
                $_SESSION['success'] = 'Data updated';
            } else { 
                $_SESSION['err'] = 'Unable to update data';
            }
        }
    } else {
        $q = pdoRun($db,
            "INSERT INTO Responses (resource_id, user_id, data, sourcedid) VALUES (?, ?, ?, ?)",
            Array($context->getResourceID(), $context->getUserID(),
            $_POST['response'], $context->getOutcomeSourceDID()) 
        );
        if ($q->success) $rows = $q->rowCount();
        if ( $rows > 0 ) {
            $_SESSION['success'] = 'Data inserted';
        } else { 
            $_SESSION['err'] = 'Unable to insert data ';
        }
    }
    $context->redirect('index.php');
    return;
}

// Switch to view / controller
headerContent();
flashMessages();

if ( $context->isInstructor() ) {
    $sql = "SELECT * FROM Responses JOIN LTI_Users
                    ON Responses.user_id = LTI_Users.id WHERE resource_id=?";
    $q = $db->prepare($sql);
    $success = $q->execute(Array($context->getResourceID()));
    $first = true;
    while ( $response = $q->fetch() ) {
        if ( $first ) {
            echo('<table width="100%"><tr><th>Student</th><th>Response</th><th>Notes</th><th>Grade</th></tr>'."\n");
            $first = false;
        }
        echo("<tr><td>");
        if ( strlen($response['image']) > 0 ) {
            echo('<img src="'.$response['image'].'" width="30" height="30" style="float:left">');
        }
        echo(htmlentities($response['name']));
        echo('</td><td>');
        echo(htmlentities(substr($response['data'],0,60)));
        echo('</td><td>');
        echo(htmlentities(substr($response['note'],0,60)));
        echo('</td><td>');
        echo(htmlentities($response['grade']));
        echo(' <a href="grade.php?response_id='.$response[0].'">Grade</a>');
        echo('</td></tr>'."\n");
    }
    if ( $first ) {
        echo("<p>No Student Responses Found</p>\n");
    } else {
        echo("</table>\n");
    }
}

$q = pdoRun($db,
    "SELECT * FROM Responses WHERE resource_id=? AND user_id=? LIMIT 1",
    Array($context->getResourceID(), $context->getUserID())
);
$response = $q->fetch();
$text = false;
if ( $response ) {
   $text = $response['data'];
}

?>
<form  method="post">
<textarea name="response" rows="10" cols="60">
<?php echo($text); ?>
</textarea><br/>
<input type="submit" value="Submit">
</form>
<?php
footerContent();
