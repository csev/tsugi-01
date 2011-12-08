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

//Placeholder for additional announcement wall functions 