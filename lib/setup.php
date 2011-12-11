<?php

// We do not use cookies to maintain session when we are called 
// directly as a /mod/ - if we are called as /tool/ we use cookies

$serverpath = $_SERVER['REQUEST_URI'];
$pos = strpos($serverpath,'?');
if ( $pos > 0 ) $serverpath = substr($serverpath,0,$pos);
global $RUNNING_IN_FRAME, $RUNNING_IN_TOOL;
$RUNNING_IN_FRAME = (strpos($serverpath, '/mod/') !== false);
$RUNNING_IN_TOOL = (strpos($serverpath, '/tool/') !== false);

if ( $RUNING_IN_FRAME && ! defined('COOKIE_SESSION') ) {
    ini_set('session.use_cookies', '0');
    ini_set('session.use_only_cookies',0);
    ini_set('session.use_trans_sid',1); 
}

// We put debug log data into session as soon as we detect 
// a session so it survives a redirect
global $DEBUG_STRING;
$DEBUG_STRING='';

function debugClear() {
    global $DEBUG_STRING;
    unset($_SESSION['__zzz_debug']);
}

function debugLog($text,$mixed=false) {
    global $DEBUG_STRING;
    $sess = (strlen(session_id()) > 0 );
    if ( $sess ) {
        if ( strlen($DEBUG_STRING) > 0 && strlen($_SESSION['__zzz_debug']) > 0) {
            $_SESSION['__zzz_debug'] = $_SESSION['__zzz_debug'] ."\n" . $DEBUG_STRING;
        } else if ( strlen($DEBUG_STRING) > 0 ) {
            $_SESSION['__zzz_debug'] = $DEBUG_STRING;
        }
        $DEBUG_STRING = $_SESSION['__zzz_debug'];
    }
    if ( strlen($text) > 0 ) {
        if ( strlen($DEBUG_STRING) > 0 ) {
            if ( substr($DEBUG_STRING,-1) != "\n") $DEBUG_STRING .= "\n";
        }
        $DEBUG_STRING .= $text;
    }
    if ( $mixed !== false ) {
        if ( strlen($DEBUG_STRING) > 0 ) {
            if ( substr($DEBUG_STRING,-1) != "\n") $DEBUG_STRING .= "\n";
        }
        if ( $mixed !== $_SESSION ) {
            $DEBUG_STRING .= print_r($mixed, TRUE);
        } else { 
            $tmp = $mixed;
            unset($tmp['__zzz_debug']);
            $DEBUG_STRING .= print_r($tmp, TRUE);
        }
    }
    if ( $sess ) { // Move debug to session.
        $_SESSION['__zzz_debug'] = $DEBUG_STRING;
        $DEBUG_STRING = '';
        // echo("<br/>=== LOG $text ====<br/>".$_SESSION['__zzz_debug']."<br/>\n");flush();
    }
}

// Calling this clears debug buffer...
function debugDump() {
    global $DEBUG_STRING;
    $retval = '';
    $sess = (strlen(session_id()) > 0 );
    if ( $sess ) { 
        // echo("<br/>=== DUMP ====<br/>".$_SESSION['__zzz_debug']."<br/>\n");flush();
        if (strlen($_SESSION['__zzz_debug']) > 0) {
            $retval = $_SESSION['__zzz_debug'];
            unset($_SESSION['__zzz_debug']);
        }
    }
    if ( strlen($retval) > 0 && strlen($DEBUG_STRING) > 0) {
        $retval .= "\n";
    }   
    if (strlen($DEBUG_STRING) > 0) {
        $retval .= $DEBUG_STRING;
        $DEBUG_STRING = '';
    }
    return $retval;
}

function dumpPost() {
        print "<pre>\n";
        print "Raw POST Parameters:\n\n";
        ksort($_POST);
        foreach($_POST as $key => $value ) {
            if (get_magic_quotes_gpc()) $value = stripslashes($value);
            print "$key=$value (".mb_detect_encoding($value).")\n";
        }
        print "</pre>";
}

function dumpSession() {
        print "<pre>\n";
        print_r($SESSION);
        print "</pre>";
}

function lmsDie($message=false) {
    global $CFG, $DEBUG_STRING;
    if($message !== false) echo($message);
    if ( $CFG->development === TRUE ) {
        if ( strlen($DEBUG_STRING) > 0 ) {
            echo("\n<pre>\n");
            echo(htmlentities($DEBUG_STRING));
            echo("\n</pre>\n");
        }
    }
    die();
}

// No trailer

