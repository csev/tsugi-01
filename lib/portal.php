<?php

function flashMessages() {
    if ( isset($_SESSION['err']) ) {
        echo '<p style="color:red">'.$_SESSION['err']."</p>\n";
        unset($_SESSION['err']);
    }
    if ( isset($_SESSION['success']) ) {
        echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
        unset($_SESSION['success']);
    }
}

function doCSS($context=false) {
    global $CFG;
    echo '<link rel="stylesheet" type="text/css" href="'.$CFG->wwwroot.'/static/css/default.css" />'."\n";
    if ( $context !== false ) {
        foreach ( $context->getCSS() as $css ) {
            echo '<link rel="stylesheet" type="text/css" href="'.$css.'" />'."\n";
        }
    }
}

// TODO: deal with headers sent...
function requireLogin() {
    global $CFG;
    if ( ! isset($_SESSION['user_id']) ) {
        $_SESSION['err'] = 'Login required';
        doRedirect($CFG->wwwroot.'/login.php') ;
        exit();
    }
}

function requireAdmin() {
    global $CFG;
    if ( $_SESSION['admin'] != 'yes' ) {
        $_SESSION['err'] = 'Login required';
        doRedirect($CFG->wwwroot.'/login.php') ;
        exit();
    }
}

function headerContent($head=false) {
    global $HEAD_CONTENT_SENT, $CFG, $RUNNING_IN_TOOL;
    if ( $HEAD_CONTENT_SENT === true ) return;
    header('Content-Type: text/html; charset=utf-8');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
   <link href="<?php echo($CFG->wwwroot); ?>/static/css/default.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?php echo($CFG->wwwroot); ?>/static/js/jquery.min.js">
    </script>
<?php if ( $head !== false ) echo($head); ?>
  </head>
<body>
<?php
    $HEAD_CONTENT_SENT = true;
    if ( $RUNNING_IN_TOOL ) {
        userMenu('Test');
        echo("\n<hr>\n");
    }
}

function adminMenu() {
    requireAdmin();
    global $CFG;
    headerContent();
    echo('<div id="adminheader">');
    echo('<h1>Admin Functions</h1>');
    echo("<ul>\n");
    echo('<li><a href="'.$CFG->wwwroot.'/users/index.php">Users</a><li>');
    echo('<li><a href="'.$CFG->wwwroot.'/courses/index.php">Courses</a></li>');
    echo('<li><a href="'.$CFG->wwwroot.'/courses.php">Non-Admin</a></li>');
    echo('</ul></div><br clear="all">'."\n");
}

function userMenu($title=false) {
    global $CFG, $RUNNING_IN_TOOL;
    headerContent();
    $modules = getModules();
    echo('<div id="header">');
    if ( $title === false && strlen($_SESSION['_context_course_key']) > 0 ) $title = $_SESSION['_context_course_key'];
    if ( $title === false ) $title = 'L.M.S.';
    echo('<h1><a href="http://www.imsglobal.org/" target="_new"><img src="'.$CFG->wwwroot.'/static/image/logo.png" alt="l.m.s." width="200" height="30"/></a></h1>');
    echo('<ul>');
    if ( strlen($_SESSION['user_name']) > 0 ) {
        $id = $_GET['id'];
        if ( !isset($id) ) $id = $_SESSION['_context_course_id'];
        if ( isset($id) ) {
            foreach ( $modules as $module ) {
                if ( is_file($CFG->dirroot.'/mod/'.$module.'/index.php') ) {
                    if ( $RUNNING_IN_TOOL ) {
                        echo('<li><a href="'.$CFG->wwwroot.'/tool/'.$module.'/index.php">'.ucwords($module).'</a></li>');
                    } else {
                        echo('<li><a href="'.$CFG->wwwroot.'/course.php?id='.$id.'&mod='.$module.'">'.ucwords($module).'</a></li>');
                    }
                } else {
                    // echo('<li>'.ucwords($module).'</li>');
                }
            }
        }
        echo('<li><a href="'.$CFG->wwwroot.'/courses.php">Courses</a></li>');
        echo('<li><a href="'.$CFG->wwwroot.'/logout.php">Logout');
        echo(' ('.$_SESSION['user_name'].')');
        echo('</a></li> ');
    } else {
        echo("<li>Please Log In</li>");
    }
    echo('</ul></div><br clear="all">'."\n");
}

function footerContent() {
    global $CFG;
    $debug = debugDump();
    if ( $CFG->development === TRUE ) {
        if ( strlen($debug) > 0 ) { ?>
<br/>
<a href="#" onclick="$('#debugpre').toggle();return false;" 
        style="position: absolute; right:0; bottom:40; color:grey; text-decoration: none; font-size: small;">Debug Log</a>
<pre id="debugpre" style="display: none;">
<?php echo($debug); ?>
</pre>
<?php
        }
    }
    echo("</body>\n</html>\n");
}

function addSession($location) {
    if ( stripos($location, '&'.session_name().'=') > 0 ||
         stripos($location, '?'.session_name().'=') > 0 ) return $location;

    if ( strpos($location,'?') > 0 ) {
       $location = $location . '&';
    } else {
       $location = $location . '?';
    }
    $location = $location . session_name() . '=' . session_id();
    return $location;
}

// Forward to a local URL, adding session if necessary - not that hrefs get altered appropriately 
// by PHP itself
function doRedirect($location) {
    if ( headers_sent() ) {
        echo('<a href="'.htmlentities($location).'">Continue</a>'."\n");
    } else {
        if ( ini_get('session.use_cookies') == 0 ) {
            $location = addSession($location);
        }
        header("Location: $location");
    }
}