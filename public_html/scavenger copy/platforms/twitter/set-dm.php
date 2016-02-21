<?php
require 'html.inc';
require 'header.php';
require 'config.php';
require 'functions.php';

$directMessage = $_POST['directMessage'];

echo '<form class="copy_form" method="POST" >';
	echo '<textarea class="direct_message_textarea" maxlength="160" placeholder="160 character limit" type="text" name="directMessage" tabindex="0"></textarea>';
	echo '<button class="button" type="submit" name="submitDirectMessage" tabindex="2">Set Direct Message</button>';
echo '</form>';


$path = dirname ( __FILE__ );
$file = "$$dataFileDirectory/direct-message.txt";

if ($directMessage) {
	unlink($file);
	$myfile = fopen("$file", "w") or die("Unable to open file!");
	
	// Write the contents to the file, 
	// using the FILE_APPEND flag to append the content to the end of the file
	// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
	
	file_put_contents($file, $directMessage, FILE_APPEND | LOCK_EX);
	fclose($myfile);
}

echo '<h3>Current Direct Message:</h3>';
echo '<div>';
$directMessageUpdated = file_get_contents($file);
echo $directMessageUpdated;
echo '</div>';	
?>