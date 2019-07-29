<!-- This page will be used to delete PHP post -->
<?php
	include 'config.php';	//includes the important configurations for databases
	session_start();
	
	$tbl_name="news";	// Table name
	$error = false;	//flag is false by default

	// Connect to server and select database.
	mysql_connect("$host", "$username", "$password")or die("cannot connect");
	mysql_select_db("$db_name")or die("cannot select DB");

	if(!isset($_SESSION['admin']) || !$_SESSION['admin'])		
		header("Location: ./deauth.php");	//redirect to login if user is not admin //die("<a href='./logout.php'>Redirect to logout.</a>"); //

	else if(isset($_GET['id'])) {
		$id = $_GET['id'];
		if($_SESSION['admin']==1) {	//if user is admin
			$sql = "DELETE FROM $tbl_name where artid='$id'";
			$result = mysql_query($sql);
			if($result) {
				header("Location: ./drawer.php?page=homepage"); //die("<a href='./drawer.php?page=homepage'>Redirect to homepage</a>"); //
				echo "Post modified.";
			} else $error = true;
		} else $error = true;
	} else $error = true;
?>