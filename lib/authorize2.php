<?php
/**
 * PHP version 5
 *
 * @category  File_Uploading
 * @package   RLME\RLAPI
 * @author    Samuel Simão <samuel@pomaire.com.br>
 * @author    George Tsatsis <admin@ratelimited.me>
 * @copyright 2017-2018 RATELIMITED, LLC
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @version   GIT: <git_id>
 * @link      https://ratelimited.me
 * @see       pg_*
 * @since     File available since RLAPI 2.0
 */
/* This project requires */
require '../vendor/autoload.php'; // Composer Autoloader
require '../speechlines.inc.php'; // Speech Lines ($messages for the user)
require '../../../../rl1-pgdbcreds.inc.php'; // Database Credentials

/**
 * Authenticate a user token
 *
 * @param string $token A service token
 * 
 * @return void
 */
function authenticate($token)
{
    require('../../../../rl1-pgdbcreds.inc.php');
    /**
     * Make allowed variable global
     * 
     * @todo Avoid globals replacing them with something else
     */
    global $allowed;

    $token = explode(' ', $token);

    /* Prepare PostgreSQL query */
    $tokencomparison = pg_prepare(
        $database,
        "fetch-token-by-token",
        "SELECT * FROM tokens WHERE token = $1"
    );
    /* Execute PostgreSQL query */
    $tokencomparisonresult = pg_execute(
        $database, "fetch-token-by-token", $token
    );
    // The row that pulled from the database
    $tokenrow = pg_fetch_object($tokencomparisonresult);
        
    /**
     * Assume tokenrow went all right and begin authenticating
     * 
     * @todo Error handling
     */
    if ($tokenrow) {
        // User id
        $uid = $tokenrow->user_id;
        // Query to select user from database
        $userGet = "SELECT * FROM users WHERE id = '$uid'";
        // Execute query
        $userResult = pg_query($database, $userGet);
        // The row containing data
        $userRow = pg_fetch_object($userResult);
        // Fetch whether the user is valid or not to a variable
        $isblocked = $userRow->is_blocked;
        /* If we got an allowed user, allowed = true and vice-versa */
        if ($isblocked == 't') {
            $allowed = false;
        }
        if ($isblocked == 'f') {
            $allowed = true;
        }

        if(is_null($isblocked) or empty($isblocked)) {
            $allowed = true;
        }
    }
}
