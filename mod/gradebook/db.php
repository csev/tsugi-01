<?php
global $db;

// My data for this application
makeTable($db, 'Gradebook', Array(
    'id:key',
    'item_id:integer',
    'grade:text:maxlength=32',
	)
);

makeTable($db, 'Items', Array(
    'id:key',
    'user_id:integer',
    'wall_id:integer',
    'answer:text:maxlength=32',
	)
);

?>