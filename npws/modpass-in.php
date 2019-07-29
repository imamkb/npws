<?php 
	if(!isset($included)) header("Location: ./drawer.php?page=modpass");

	$tbl_name="accounts";	// Table name
	$error = false;	//flag is false by default

	// Connect to server and select database.
	mysql_connect("$host", "$username", "$password")or die("cannot connect");
	mysql_select_db("$db_name")or die("cannot select DB");

	if(!isset($_SESSION['name'])) die("You are not logged in. <a href='./index.php'>Go to login.</a>");//header("Location: ./index.php");	//if user is not logged in, redirect to login page...
	
	else if(isset($_SESSION['name']) && isset($_POST['oldpass']) && isset($_POST['pass']) && isset($_POST['confirm']) && $_POST['pass']==$_POST['confirm']) {
		//if form is properly fed and the passwords match...
		$oldpass = $_POST['oldpass'];
		$pass = $_POST['pass'];
		$sess = $_SESSION['name'];

		$oldhash = hash_hmac("sha256",$oldpass,$oldpass);	//hashes the password using hmac(trivial approach)
		$sql="SELECT * FROM $tbl_name WHERE name='$sess' and pass='$oldhash'";
		$result=mysql_query($sql);	//above SQL query executes and provides a matching result

		if($result && mysql_num_rows($result)==1 && strlen($_POST['pass'])>=8) {
			
			$hash = hash_hmac("sha256",$pass,$pass);	//hashes the password using hmac(trivial approach)
			
			$sql="UPDATE $tbl_name SET pass='$hash' where pass='$oldhash' and name='$sess'";
			$result=mysql_query($sql);	//above SQL query executes and provides a matching result
			if($result) {
				echo "Successfully changed password.";
				echo "<script>location.assign('./drawer.php?page=homepage');</script>";
				//die("<br><a href='./drawer.php?page=homepage'>Go to homepage.</a>"); //header("Location: ./drawer.php?page=homepage");
			} else $error=true;
		} else $error=true;			//invalid registration
	}
?>

<script>
	//script to validate the form on client-side...
	$(document).ready(function() {
		$("#status").hide();	//hide the error by default...
		$("#short").hide();
		$("#pass").change(function() {
			if($("#pass").val().length<8)	//show if error...
				$("#short").show(500);
			else 	// else hide the error back...
				$("#short").hide(500);
		});
		$("#confirm").change(function() {
			if($("#pass").val()!=$("#confirm").val())	//show if error...
				$("#status").show(500);
			else 	// else hide the error back...
				$("#status").hide(500);
		});
	});
</script>

<div style="height: 5%;"></div>
<div class="mui-panel mui-container-fluid" style="width: 90%;">
	<form method="post" enctype="application/json" class="mui-form">
		<legend>Change your password</legend>
		<table class="mui-table" style="width: 100%;">
			<tr><td>
				<span>You can change your password here. You need to know your old password to change to a new one.</span>
			</td></tr>
			<?php if($error) { ?>
			<tr><td>
				<span style="font-family: 'Open Sans',sans-serif;color:red; animation: 500ms fadeIn;">
					<b>Changing password Failed</b>.<br><em>The password was not accepted(it may be too short), or the passwords did not match. Please try again.</em>
				</span>
			</td></tr>
			<?php } ?>
			<tr><td>
				<div class="mui-textfield mui-textfield--float-label">
				<input type="password" name="oldpass" id="oldpass" required="true" autocomplete="OFF" class="mui--no-user-select">
				<label for="name">Old Password</label>
				</div>
			</td></tr>
			<tr><td>
				<div class="mui-textfield mui-textfield--float-label">
				<input type="password" name="pass" id="pass" required="true" autocomplete="OFF" class="mui--no-user-select">
				<label for="pass">New Password</label>
				<span id="short" style="color: red;" class="mui--text-caption">Password too short. Should be at least 8 characters long.</span>
				</div>
			</td></tr>
			<tr><td>
				<div class="mui-textfield mui-textfield--float-label">
				<input type="password" name="confirm" id="confirm" required="true" autocomplete="OFF" class="mui--no-user-select">
				<label for="confirm">Confirm Password</label>
				</div>
				<span id="status" style="color: red;" class="mui--text-caption">Passwords do not match.</span>
			</td></tr>
			<tr><td style="text-align: right;"><button type="submit" class="mui-btn mui-btn--primary">CHANGE PASSWORD</button></td></tr>
		</table>
	</form>
</div>