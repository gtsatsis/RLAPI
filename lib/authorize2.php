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
//require('../../../../rl1-pgdbcreds.inc.php');
    /**
     * Make @allowed variable global
     * 
     * @todo Avoid globals replacing them with something else
     */
    global $allowed;

    /* Preg match the token into a sanitized filtered token variable */
    preg_match(
        "/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i",
        $token,
        $filteredToken
    );
    /* Prepare PostgreSQL query */
    $tokencomparison = pg_prepare(
        $database,
        "fetch-token-by-token",
        "SELECT * FROM tokens WHERE token = $1"
    );
    /* Execute PostgreSQL query */
    $tokencomparisonresult = pg_execute(
        $database, "fetch-token-by-token", $filteredToken
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
        // Fetch wheter the user is valid or not to a variable
        $isblocked = $userRow->is_blocked;
        /* If we got an allowed user, allowed = true and vice-versa */
        if ($isblocked == 't') {
            echo $tokenIsBlocked;
            $allowed = "false";
        } elseif ($isblocked == 'f' || null) {
            $allowed = "true";
        }
    }
}
