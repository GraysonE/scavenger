<div property="mainContentOfPage">
  <?php //the_content(); ?>

<?php	
$stylesheetPath = get_stylesheet_directory_uri();
	//echo $stylesheetPath;
require("$stylesheetPath/assets/php/config.php");
include("$stylesheetPath/functions.php");

get_template_part( 'assets/php/config' );

global $connection;
print_r($connection);
?>


<form class="input" method="POST" action="<?php echo get_permalink(); ?>">
	<label>Twitter User</label>
	<input type="text" name="twitterUser"/>
	<?php echo '<input type="hidden" name="connection" value="'.$GLOBALS['connection'].'"/>'; ?>
	<?php echo '<input type="hidden" name="myScreenName" value="'.$myScreenName.'"/>'; ?>
	<?php echo '<input type="hidden" name="count" value="'.$count.'"/>'; ?>
	<?php echo '<input type="hidden" name="sleep" value="'.$sleep.'"/>'; ?>
	<?php echo '<input type="hidden" name="debug" value="'.$debug.'"/>'; ?>
	<?php echo '<input type="hidden" name="numberOfFollowers" value="'.$numberOfFollowers.'"/>'; ?>
	<?php echo '<input type="hidden" name="cursorTarget" value="'.$cursorTarget.'"/>'; ?>
	<button type="submit" name="submitTargetAccount">Submit</button>
</form>



<?php
	
	
if (isset($_POST['submitTargetAccount'])) {
	echo 'submitted target account';
}
$twitterUser = $_POST['twitterUser']; 
	if($debug){ echo "twitterUser: $twitterUser";}
$connection = $_POST['connection']; 
	if($debug){ echo "connection: $connection";}
$myScreenName = $_POST['myScreenName']; 
	if($debug){ echo "myScreenName: $myScreenName";}
$count = $_POST['count']; 
	if($debug){ echo "count: $count";}
$sleep = $_POST['sleep']; 
	if($debug){ echo "sleep: $sleep";}
$debug = $_POST['debug']; 
	if($debug){ echo "debug: $debug";}
$numberOfFollowers = $_POST['numberOfFollowers']; 
	if($debug){ echo "numberOfFollowers: $numberOfFollowers";}
$cursorTarget = $_POST['cursorTarget']; 
	if($debug){ echo "cursorTarget: $cursorTarget";}


if (isset($twitterUser)) {
	echo '<br><br>';
	echo '<strong>twitterUser: '.$twitterUser.'</strong>';
	echo '<br>';
	
	if($debug){echo'execute function';}
	//copy_followers($connection, $myScreenName, $count, $sleep, $debug, $numberOfFollowers, $cursorTarget);
	
}

?>
<!-- onsubmit="copy_followers_ajax(<?php echo "$connection, $myScreenName, $count, $sleep, $debug, $numberOfFollowers, $cursorTarget";?>);" -->

</div>
<!-- /.main-body -->
