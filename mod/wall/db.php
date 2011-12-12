<?php

global $db;

// My data for this application
makeTable($db, 'Announcements', Array(
    'id:key',
    'user_id:integer',
    'data:text:maxlength=2048',
    'datetime:text:maxlength=50',
	)
);

makeTable($db, 'User_Files', Array(
    'id:key',
    'name:text:maxlength=255',
    'mime:text:maxlength=50',
    'size:integer',
    'data:mediumblob',
    'datetime:text:maxlength=50',
	)
);

makeTable($db, 'Replies', Array(
    'id:key',
    'announcement_id:integer',
    'user_id:integer',
    'data:text:maxlength=2048',
    'datetime:text:maxlength=50',
	)
);

?>
