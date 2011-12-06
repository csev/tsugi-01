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

?>
