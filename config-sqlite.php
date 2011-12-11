<?php // Configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

// Are running in development mode
$CFG->development = TRUE;

// No trailing slash
$CFG->wwwroot = 'http://localhost/tsugi';

$CFG->dirroot = realpath(dirname(__FILE__));
// $CFG->dataroot  = '/tmp/tsugi'; // If unset use temporary space

$CFG->pdo    = 'mysql:host=localhost;dbname=lms';
$CFG->pdo    = 'sqlite:'.$CFG->dirroot.'/db/response.sqlite';
$CFG->pdouser    = 'lmsuser';
$CFG->pdopass    = 'lmspassword';

$CFG->localkey    = 'local';   // oauth_consumer_key for local launches
$CFG->localkeyid   = -1;   // For locally created accounts (don't change)
$CFG->defaultkeyid = -2;   // For dev launches when there are no LTI_Keys defined (don't change)

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);

require_once(dirname(__FILE__) . '/lib/setup.php');
require_once(dirname(__FILE__) . '/lib/portal.php');
require_once(dirname(__FILE__) . '/lib/module.php');

// No trailing tag to avoid white space
