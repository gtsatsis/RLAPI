<?php

function generateFileName($extension)
{
    global $fileName;
    // Generate a random name
    $fileName = substr(
        str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 6)),
        0,
        6
    );
    
    // Add file extension
    $fileName .= "." . $extension;

    return $fileName;
}

function isUnique($filename)
{
    $headers = get_headers("https://ratelimited.me/" . $filename);
    if (substr($headers[0], 9, 3) == "404") {
        return true;
    }
    return false;
}