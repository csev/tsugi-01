<?php

function getFolderName($context)
{
    $foldername = $context->getResourceKey();
    $foldername = md5($foldername);
    $root = sys_get_temp_dir();
    if ( isset($CFG->dataroot) ) $root = $CFG->dataroot;
    $root = $root . '/dropbox';
    if ( !file_exists($root) ) mkdir($root);
    $foldername = $root.'/' . $foldername;
    return $foldername;
}

function getStudentFolder($context)
{
    $foldername = $context->getResourceKey();
    $userkey = $context->getUserKey();
    $foldername = md5($foldername);
    $userkey = md5($userkey);
    $foldername = dirname(__FILE__).'/upload/' . $foldername . '-students/' . $userkey . '/';
    return $foldername;
}

function fixFileName($name)
{
    $new = str_replace("..","-",$name);
    $new = str_replace("/", "-", $new);
    $new = str_replace("\\", "-", $new);
    $new = str_replace("\\", "-", $new);
    $new = str_replace(" ", "-", $new);
    return $new;
}

function establishContext() {
    $sessid = $_REQUEST['xsession'];
    if ( ! isset($sessid) ) {
        die("Session failure");
    }

    session_id($sessid);
    session_start();
    // We want this to come form the session, not from the secret - this is not launchable
    $context = new BLTI(rand()+"xyzzy", true, false);
    return $context;
}

?>