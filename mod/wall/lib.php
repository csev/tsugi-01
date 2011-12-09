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
    $target_path = $CFG->wwwroot;
    $target_path = $target_path . "/" . basename( $_FILES['uploadedFile']['name']);
    echo "Upload: " . $_FILES["uploadedFile"]["name"] . "<br />";
    echo "Type: " . $_FILES["uploadedFile"]["type"] . "<br />";
    echo "Size: " . ($_FILES["uploadedFile"]["size"] / 1024) . " Kb<br />";
    echo "Stored in: " . $_FILES["uploadedFile"]["tmp_name"] . "<br />";
    echo $target_path . "<br />";

    $name = $db->$_FILES['uploadedFile']['name'];
    $mime = $db->$_FILES['uploadedFile']['type'];
    //$data = $db->file_get_contents($_FILES  ['uploadedFile']['tmp_name']);
    $size = intval($_FILES['uploadedFile']['size']);
 

    $sql = "INSERT INTO User_Files () VALUES ()";
    $q = $db->prepare($sql);
    $success = $q->execute(Array() );
    // Execute the query
    $result = $db->prepare($query);
    $result->execute();
    
    //if (move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $target_path) ) {
    //    $_SESSION['success'] = "The file ".  basename( $_FILES['uploadedFile']['name']). 
    //    " has been uploaded";
    //} else{
    //    $_SESSION['err'] = "Unable to insert data -- Error Code " .$_FILES['uploadedFile']['error'];
    //}

}
