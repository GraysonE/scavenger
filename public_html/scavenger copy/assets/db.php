<?php

// DATABASE CONFIG

$db = new mysqli('localhost', 'grayson_erhard', 'gceDeve10per!', 'grayson_scavenger');

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}

?>