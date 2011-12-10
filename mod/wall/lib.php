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

function getPostFileList() {
}

function deletePostFile() {
}

