<?php

function postToWall($db,$responseData) {
    date_default_timezone_set('EST');
    $sql = "INSERT INTO Announcements (user_id, data, datetime) VALUES (?, ?, ?)";
    $q = $db->prepare($sql);
    $success = $q->execute(Array($_SESSION['user_id'],$responseData,date('M d, Y g:i a') ));

    // echo($sql);flush();  
    if ( $success) $rows = $q->rowCount();
    if ( $rows > 0 ) {
        $_SESSION['success'] = 'Data inserted';
    } else { 
        $_SESSION['err'] = 'Unable to insert data ';
    }    
}


function addFileToPost($db, $_FILES) {
    date_default_timezone_set('EST');
    $fileSize = intval($_FILES["uploadedFile"]["size"] / 1024);
    echo "Upload: " . $_FILES["uploadedFile"]["name"] . "<br />";
    echo "Type: " . $_FILES["uploadedFile"]["type"] . "<br />";    
    echo "Size: " . $fileSize . " Kb<br />";
    echo "Stored in: " . $_FILES["uploadedFile"]["tmp_name"] . "<br />";
 
    $sql = "INSERT INTO User_Files (name, mime, size, data, datetime) 
            VALUES (?, ?, ?, ?, ?)";
    $q = $db->prepare($sql);
    $success = $q->execute(Array($_FILES['uploadedFile']['name'],$_FILES['uploadedFile']['type'],file_get_contents($_FILES  ['uploadedFile']['tmp_name']),$fileSize, date('M d, Y g:i a') ) );

    if ( $success) $rows = $q->rowCount();
    if ( $rows > 0 ) {
        $_SESSION['success'] = "The file ".  basename( $_FILES['uploadedFile']['name']). 
        " has been uploaded";
    } else { 
        $_SESSION['err'] = "Unable to insert data -- Error Code " .$_FILES['uploadedFile']['error'];
    }    
}


function getPostFileList($db) {
    $sql = "SELECT * FROM User_Files ORDER BY User_Files.id DESC;";
    $q = $db->prepare($sql);
    $q->execute();
    $first = true;

    while ( $q && $row = $q->fetch() ) {
        if ( $first ) {
            $first = false;
        }
        echo ("<tr><td>");
        echo ('<table class="post-file-list"><tr>');
        echo('<td width="570"><table width="570"><tr>');
        echo("<td>".$row['name']." ".$row['datetime']."</td></tr>");
        echo('<tr><td><a href="index.php?deleteFile='.$row[0].'">Delete</a></td></tr></table>');
        echo("</td></tr></table>");
        echo("</td></tr>\n");
    }
}


function deletePostFile($db, $PostFileID) {
    $q = pdoRun($db, "DELETE FROM User_Files WHERE id=?",$PostFileID);
}

