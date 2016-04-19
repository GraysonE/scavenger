<?php
$fromConfig = stripslashes($argv[2]);
if ($fromConfig) {
	
	$clientFolder = stripslashes($argv[1]);
		if($debug) { echo "clientFolder: $clientFolder";}
	$clientFolderFromCF = $clientFolder;
}
require 'config.php';
require 'html.inc';
require 'header.php';
require "$dataFileDirectory/user-cursor.php";
require 'functions.php';

if ($debug) {
	echo '<h2>@'.$myScreenName.'</h2>';
	echo '<h3>'.$numberOfFollowers.' followers</h3>';	
}


if($debug) {
	echo "twit_user: $twitterUser<br>";
	echo "cursorTarget_post: $cursorTarget_post<br>";
	echo "cursorTarget$cursorTarget<br>";
	echo "myScreenName: $myScreenName<br>";
		
}

echo '<div class="wrap">'; 

if (($myScreenName == '')) {
	die('User screen name not set!');
} elseif ($twitterUser == '') {
		die('Twitter username to follow not set! Please set the user <a href="set-user.php">here</a>.');
} else {
			
	if (($cursorTarget == '') && ($cursorTarget_post == '')) {
		
		$cursorTarget = "-1";
		
	} else {
	
		$cursorTarget = $cursorTarget_post;
		
	}
	
	if($debug) { 
		echo "twit_user: $twitterUser<br>";
		echo "cursorTarget: $cursorTarget<br>";
		echo "myScreenName: $myScreenName<br>";
		
	}
}	
	


	
$canFollow = follow_check($connection, $debug, $twitterUser, $cursorTarget);
	if($debug) {echo "canFollow: $canFollow<br>";}
	
	if ($canFollow) {
			
		loading_gif();	
	
		
	
		$cursorTarget = copy_followers($connection, $db, $myScreenName, $count, $targetCount, $debug, $cursorTarget, $twitterUser, $date, $email, $debugEmails, $name);
	
	
	
		if ($debug){echo "<h1>Next Cursor: $cursorTarget</h1>";}
		
			?>
				<form class="next_cursor" method="POST" >
					<input type="hidden" name="cursorTarget_post" value="<?php echo $cursorTarget;?>" />
					<input type="hidden" name="twitterUser_post" value="<?php echo $twitterUser;?>"/> 
					<button type="submit" name="submitTargetAccount">Follow More</button>
				</form>
			<?php
			
			
			
			
		if ($cursorTarget == 0) {
			$cursorTarget = "-1";
			
			// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "To: $name" . "\r\n";
			$headers .= 'From: Scavenger App <grayson@gator3029.hostgator.com>' . "\r\n";
	
			// The message
			$message ="<html><head><title>Scavenger App Mail</title></head><body>";
	
			$message .= "<div>You reached the end of @$twitterUser's followers. If you don't want the app to automatically go back through from the start, go to <a href='http://scavenger-app.com//scavenger/set-user.php'>Set User</a> to follow a different user list.</div>";
			$message .="<br><br>Sincerely,<br>The Scavenger App";
	
			$message .= "</body></html>";
	
			// In case any of our lines are larger than 70 characters, we should use wordwrap()
			$message = wordwrap($message, 70, "\r\n");
	
			// Send
			mail( $email, "@$twitterUser Complete", $message, $headers);
			
		}
		
		
		$time = $date->format('h:i:s m-d-Y');
			
		$path = dirname ( __FILE__ );
		$file = "$dataFileDirectory/user-cursor.php";
		$directory = "$path/*";
		if (!$frustratingHosting) {
			chmod ( $file , 0600 );
		}
		
		$current_date = date(d);
	
		if ($current_date > 27) {
			unlink($file);
			$myfile = fopen("$file", "w") or die("Unable to open file!");
			fclose($myfile);
		}	
		
		$content = "\n".'<?php /*'.$time.' - set by copy-followers.php*/ $cursorTarget_post="'.$cursorTarget.'";?>';
		
		file_put_contents($file, $content, FILE_APPEND | LOCK_EX);
				
	} elseif ($canFollow == '161') {
		echo "You've reached the following limit.";
		
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "To: $name" . "\r\n";
		$headers .= 'From: Scavenger App <grayson@gator3029.hostgator.com>' . "\r\n";

		// The message
		$message ="<html><head><title>Scavenger App Mail</title></head><body>";

		$message .= "<div>You capped Twitter's follow limit. Be sure not to manually follow or especially UNFOLLOW anyone for at least six hours or Twitter will suspend your account.</div>";
		$message .="<br><br>Sincerely,<br>The Scavenger App";

		$message .= "</body></html>";

		// In case any of our lines are larger than 70 characters, we should use wordwrap()
		$message = wordwrap($message, 70, "\r\n");

		// Send
		mail( $email, "@$twitterUser Complete", $message, $headers);
		
	} elseif ($canFollow == '88') {
		echo "<div class='errorMessage'>You've reached the rate limit. Wait 15 minutes to refresh your personal profile look up.</div>";
	}

echo '</div>';
?>