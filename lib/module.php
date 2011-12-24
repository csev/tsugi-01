<?php

require_once $CFG->dirroot.'/lib/lti_util.php';

$MODULE_LIST = false;
function getModules() {
    global $CFG, $MODULE_LIST;
    if ( $MODULE_LIST !== false ) return $MODULE_LIST;
    $MODULE_LIST = Array();
    $dir = $CFG->dirroot . '/mod/';
    
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while ( ($file = readdir($dh)) !== false) {
                if ( strpos($file, '.') === 0 ) continue;
                if ( is_dir($dir . $file ) ) {
                    $MODULE_LIST[] = $file;
                }
            }
            closedir($dh);
        }
    }
    return $MODULE_LIST;
}

function moduleContext($doKeys=true) {
    global $CFG, $db;
    session_start();
    // We want this to come from the session, not from the secret - this is not launchable
    $context = new LTI(rand()+"xyzzy", true, false);
    if ( ! $context->valid ) $context;
    if ( doKeys == true ) {
        require_once $CFG->dirroot.'/db.php';
        setupPrimaryKeysPDO($db, $context);
    }
    return $context;
}


