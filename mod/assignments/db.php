<?php

global $db;

// My data for this application
makeTable($db, 'Assignments', Array(
    'id:key',
    'user_id:integer',
    'title:text:maxlength=512',
	'duedate:text:maxlegth=12'
	)
);

?>
