<?php
require('html.inc');

$twitterUser = strtolower($_POST['twitterUser']);

?>
<div class="wrap">
	
		<div class="header_wrap">
			<img class="ghost" src="/scavenger/assets/img/ghost.png"/>
			<h1>Scavenger</h1>
		</div>
		<?php if ((!isset($twitterUser)) || ($twitterUser == '')) {
		?>

		<form class="copy_form" method="POST">
			
			<input class="form_input" type="text" name="twitterUser" tabindex="0"
			placeholder="<?php if (isset($twitterUser_post)) {echo $twitterUser_post;} elseif (isset($twitterUser)){echo $twitterUser;} else { echo 'Enter twitter user here';}?>" autofocus onkeypress="preventSpace(event);" /><br>
		
			<button class="button" type="submit" name="submitTargetAccount" tabindex="2">Scavenge Followers</button>
		</form>
		<script type="text/javascript">
			function preventSpace(e) {
			   var char = e.which || e.charCode;
			   if(char == 32) {
			      e.preventDefault();
			   }
			}
		</script>
	</div>
	
	<?php 
} else {
	
	date_default_timezone_set('America/Denver');
	$date = new DateTime();	
	$time = $date->format('h:i:s m-d-Y');
	
	$file = 'config.php';
	$content = "\n".'<?php /* '.$time.' - set by set-user.php */ $twitterUser = "'.$twitterUser.'"; $cursorTarget_post = "-1";?>';
	// Write the contents to the file, 
	// using the FILE_APPEND flag to append the content to the end of the file
	// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
	file_put_contents($file, $content, FILE_APPEND | LOCK_EX);
	
	echo '<h3>Following users who follow <strong>@'.$twitterUser.'</strong>. . .</h3>';
		// Loading GIF
		echo '<div class="loading_wrap"><img src="/scavenger/assets/img/ghost-loading.gif"/></div>';
	
	?>
	
	<div id="ajax">
	</div>
	
	<script type="text/javascript">
		function ajaxRequest(){
			 var activexmodes=["Msxml2.XMLHTTP", "Microsoft.XMLHTTP"] //activeX versions to check for in IE
			 if (window.ActiveXObject){ //Test for support for ActiveXObject in IE first (as XMLHttpRequest in IE7 is broken)
			  for (var i=0; i<activexmodes.length; i++){
			   try{
			    return new ActiveXObject(activexmodes[i])
			   }
			   catch(e){
			    //suppress error
			   }
			  }
			 }
			 else if (window.XMLHttpRequest) // if Mozilla, Safari etc
			  return new XMLHttpRequest()
			 else
			  return false
		}
				
		function copyFollowers() {
				var mypostrequest=new ajaxRequest()
			mypostrequest.onreadystatechange=function(){
			 if (mypostrequest.readyState==4){
// 					 console.log(mypostrequest.responseText)
			  if (mypostrequest.status==200 || window.location.href.indexOf("http")==-1){
			   document.getElementById("ajax").innerHTML=mypostrequest.responseText
			  }
			  else{
			   alert("An error has occured making the request")
			  }
			 }
			}
			
			mypostrequest.open("GET", "copy-followers.php", true)
			mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
			mypostrequest.send(null)
		}
			
		copyFollowers();
	</script>
	
	<?php
		
}
?>
	
