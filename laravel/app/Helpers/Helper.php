<?php namespace Scavenger\Helpers;

use Carbon\Carbon;
use Scavenger\User;

class Helper {

	public static function email_admin($errorMessage, $errorCount, $controller, $screen_name) {
		
		$time = Carbon::now('America/Denver');
		$name = "Scavenger Error";
		$applicationEmail = "grayson@gator3029.hostgator.com";
		$adminEmail = "web@graysonerhard.com";

		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "To: Grayson Erhard" . "\r\n";
		$headers .= "From: $name <$applicationEmail>" . "\r\n";

		// The message
		$message = "<html><head><title>Scavenger</title></head><body>";
		// The message
		$message .= "<div>@$screen_name encountered $errorCount errors in $controller at $time.</div>";
		$message .= "<br><div>$errorMessage</div>";
		$message .= "</body></html>";

		// In case any of our lines are larger than 70 characters, we should use wordwrap()
		$message = wordwrap($message, 70, "\r\n");

		// Send
		//$success = mail($adminEmail, "Scavenger Error Report", $message, $headers);
		$success = false;
		
		if ($success) {
			echo "<h2>MAIL SENT.</h2>";
		} else {
			echo "<h2>MAIL FAILED TO SEND.</h2>";
		}
	}
	
	public static function email_user($data, $userID) {
		
		$time = Carbon::now('America/Denver');
		$name = "Scavenger Update";
		$applicationEmail = "grayson@gator3029.hostgator.com";
		$adminEmail = "web@graysonerhard.com";

		$user = User::findOrFail($userID);

		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "To: $user->first_name $user->last_name" . "\r\n";
		$headers .= "From: $name <$applicationEmail>" . "\r\n";

		// The message
		$message = "<html><head><title>Scavenger</title></head><body>";
		$message .= "<br><br><div>$data</div>";
		$message .= "</body></html>";

		// In case any of our lines are larger than 70 characters, we should use wordwrap()
		$message = wordwrap($message, 70, "\r\n");

		// Send
		//$success = mail($user->email, "Scavenger Update", $message, $headers);
		//$success = mail($adminEmail, "Scavenger Update", $message, $headers);
		$success = false;
		
		if ($success) {
			echo "<h2>MAIL SENT.</h2>";
		} else {
			echo "<h2>MAIL FAILED TO SEND.</h2>";
		}
	}
	
}