<?php
$files = glob('sessions/*');
$exceptions = ["index.html"];
foreach ($files as $file) {
    if (is_file($file) && !in_array(end(explode("/", $file)), $exceptions)) {
        if (unlink($file)) {
            echo "File: ".fileSession($file)." - Succesfully Deleted.\n";
        } else {
            echo "Cannot Delete File: ".fileSession($file)." :(\n";
        }
    }
}

function fileSession($param)
{
    return str_replace('sessions/', '', $param);
}
