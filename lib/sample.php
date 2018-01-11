<?php
function isUnique($filename)
{
    $headers = get_headers("https://ratelimited.me/" . $filename);
    if (substr($headers[0], 9, 3) == "404") {
        return true;
    }
    return false;
}
var_dump(isUnique("doot.png"));