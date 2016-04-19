<?php
// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= "To: Grayson" . "\r\n";
$headers .= 'From: Scavenger App <grayson@gator3029.hostgator.com>' . "\r\n";

// The message
$message ="<html><head><title>Success</title></head><body>";

$message .= "<div>Yay!</div><br>";

$date = new DateTime();
$time = $date->format('h:i:s m-d-Y');

$message .= "<h2>Debugging - $time</h2><br>";
// $message .= "<small>$messageDebug</small>";
$message .="<br><br>Sincerely,<br>The Scavenger App";

$message .= "</body></html>";

// In case any of our lines are larger than 70 characters, we should use wordwrap()
$message = wordwrap($message, 70, "\r\n");

// Send
mail( 'grow@graysonerhard.com', "Success!", $message, $headers);

?>