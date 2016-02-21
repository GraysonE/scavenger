<?php
	
// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= "To: Grayson" . "\r\n";
$headers .= 'From: Scavenger App <grayson@gator3029.hostgator.com>' . "\r\n";

// Send
mail( 'web@graysonerhard.com', "Test", 'test', $headers);