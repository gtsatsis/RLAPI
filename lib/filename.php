<?php

function generateFileName($extension)
{
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

function isUnique($fileName)
{
    $headers = get_headers("https://ratelimited.me/" . $fileName);
    if(substr($headers[0], 9, 3) == "404") {
        return false;
    }
    return true;
}