<?php

global $db;

// My data for this application
makeTable($db, 'Tasks', Array(
    'id:key',
    'user_id:integer',
    'title:text:maxlength=512',
	)
);

?>
