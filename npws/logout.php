<?php include 'config.php';	//includes the common configurations ?>
<?php
	session_start();
	if(isset($_SESSION['name'])) {	//if session exists in the first place
		session_destroy();	//logs you out, by closing your session...
	}
	header("Location: ./index.php");
?>
Logging you out...