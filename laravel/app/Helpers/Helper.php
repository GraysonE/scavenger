<?php namespace Scavenger\Helpers;

use Carbon\Carbon;
use Scavenger\User;

class Helper {
	
	public static function movieTitle($data) 
    {
		
	    $separator = "-";
		$ignore_words = ''; //add any words to ignore from the movie title, e.g. 'a, an, as, at, the'
		$ignore_words_regex = preg_replace(array('/^[,\s]+|[,\s]+$/', '/[,\s]+/'), array('', '\b|\b'), $ignore_words);
		
		$movieDir = strtolower(trim($data));
		$movieDir = preg_replace('/'.$ignore_words_regex.'/i', '', $movieDir);
		$movieDir = preg_replace(array('/[^a-z0-9-\s]+/', '/[\s]+/'), '', $movieDir);
		return $movieDir;  
	    
	}

	public static function email_admin($errorMessage, $screen_name) {
		
		$time = Carbon::now();
		$applicationName = "Scavenger";
		$applicationEmail = "grayson@gator3029.hostgator.com";
		$adminEmail = "web@graysonerhard.com";

		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "To: Grayson Erhard" . "\r\n";
		$headers .= "From: $applicationName <$applicationEmail>" . "\r\n";

		// The message
		$message = "<html><head><title>Scavenger</title></head><body>";
		// The message
		$message .= "<div>@$screen_name encountered an error at $time.</div>";
		$message .= "<br><div>$errorMessage</div>";
		$message .= "</body></html>";

		// In case any of our lines are larger than 70 characters, we should use wordwrap()
		$message = wordwrap($message, 70, "\r\n");

		// Send
		$success = mail($adminEmail, "Scavenger Error Report", $message, $headers);
		
		if ($success) {
			echo "<h2>MAIL SENT.</h2>";
		} else {
			echo "<h2>MAIL FAILED TO SEND.</h2>";
		}
	}
	
	public static function email_user($data, $userID) {
		
		$time = Carbon::now();
		$email = "grayson@gator3029.hostgator.com";

		$user = User::findOrFail($userID);

		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "To: $user->first_name $user->last_name" . "\r\n";
		$headers .= 'From: Scavenger <grayson@gator3029.hostgator.com>' . "\r\n";

		// The message
		$message = "<html><head><title>Scavenger</title></head><body>";
		$message .= "<br><br><div>$data</div>";
		$message .= "</body></html>";

		// In case any of our lines are larger than 70 characters, we should use wordwrap()
		$message = wordwrap($message, 70, "\r\n");

		// Send
		$success = mail($user->email, "Scavenger Update", $message, $headers);
		
		if ($success) {
			echo "<h2>MAIL SENT.</h2>";
		} else {
			echo "<h2>MAIL FAILED TO SEND.</h2>";
		}
	}
	
}