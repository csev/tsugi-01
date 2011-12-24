<?php

global $db;

makeTable($db, 'Calendar', Array(
	'id:key',
	'user_id:integer:unique=true',
	'duedate:text:maxlength=12',
	'duetime:text:maxlength=12')
	);

?>