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

function headContent($head=false) {
    global $HEAD_CONTENT_SENT;
    global $CFG;
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
}

function adminMenu() {
    requireAdmin();
    global $CFG;
    headContent();
    echo('<div id="header" style="background: yellow;">');
    echo('<h1>Admin Functions</h1>');
    echo("<ul>\n");
    echo('<li><a href="'.$CFG->wwwroot.'/users/index.php">Users</a><li>');
    echo('<li><a href="'.$CFG->wwwroot.'/courses/index.php">Courses</a></li>');
    echo('<li><a href="'.$CFG->wwwroot.'/courses.php">Non-Admin</a></li>');
    echo("</ul></div>\n");
}

function userMenu($title=false) {
    global $CFG;
    headContent();
    $modules = getModules();
    echo('<div id="header">');
    if ( $title === false ) $title = 'L.M.S.';
    echo('<h1><a href="http://www.imsglobal.org/" target="_new">'.$title.'</a></h1>');
    echo('<ul>');
    if ( strlen($_SESSION['user_name']) > 0 ) {
        if ( isset($_GET['id']) ) {
            foreach ( $modules as $module ) {
                echo('<li><a href="course.php?id='.$_GET['id'].'&mod='.$module.'">'.ucwords($module).'</a></li>');
            }
        }
        echo('<li><a href="'.$CFG->wwwroot.'/courses.php">Courses</a></li>');
        echo('<li><a href="'.$CFG->wwwroot.'/logout.php">Logout');
        echo(' ('.$_SESSION['user_name'].')');
        echo('</a></li> ');
    } else {
        echo("<li>Please Log In</li>");
    }
    echo("</ul>\n");
    echo("</div>\n");
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