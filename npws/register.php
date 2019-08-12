<?php include 'config.php';	//includes the common configurations ?>
<!DOCTYPE html>
<html>
<head>
	<title>NPWS | Register as guest</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="<?php echo $DirPublic ?>/mui.min.css" rel="stylesheet" type="text/css" />
	<script src="<?php echo $DirPublic ?>/mui.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo $DirPublic ?>/style.css">
	<meta charset="utf-8" http-equiv="X-Frame-Options" value="DENY">
	<script src="<?php echo $DirPublic ?>/jquery.min.js"></script>

	<?php		
		$tbl_name="accounts";	// Table name
		$error = false;	//flag is false by default

		// Connect to server and select database.
		mysql_connect("$host", "$username", "$password")or die("cannot connect");
		mysql_select_db("$db_name")or die("cannot select DB");
		
		if(isset($_POST['name']) && isset($_POST['pass']) && isset($_POST['confirm']) && $_POST['pass']==$_POST['confirm']) {
			//if form is properly fed and the passwords match...
			$name = $_POST['name'];
			$pass = $_POST['pass'];
			if(preg_match("/@\w+/i", $name) && strlen($_POST['pass'])>=8) {		//if username follows proper format... ie "@username123"
				$hash = hash_hmac("sha256",$pass,$pass);	//hashes the password using hmac(trivial approach)

				$sql="INSERT INTO $tbl_name(name, pass, admin) VALUES ('$name', '$hash', '0')";
				$result=mysql_query($sql);	//above SQL query executes and provides a matching result

				if($result) {
					echo "Registration successful.<br>";
					echo "You can now <a href='./index.php'>login</a>.";
					header("Location: ./index.php");
				} else $error=true;	//invalid registration
			} else $error=true;		//invalid registration
		}
	?>

	<script>
		//script to validate the form on client-side...
		$(document).ready(function() {
			$("#status").hide();	//hide the error by default...
			$("#status2").hide();
			$("#short").hide();
			$("#pass").change(function() {
				if($("#pass").val().length<8)	//show if error...
					$("#short").show(500);
				else 	// else hide the error back...
					$("#short").hide(500);
			});
			$("#name").change(function() {
				if(!$("#name").val().match(/@\w+/i))
					$("#status2").show(500);
				else 	// else hide the error back...
					$("#status2").hide(500);
			});
			$("#confirm").change(function() {
				if($("#pass").val()!=$("#confirm").val())	//show if error...
					$("#status").show(500);
				else 	// else hide the error back...
					$("#status").hide(500);
			});
		});
	</script>

</head>
<body style="font-family: 'Calibri',sans-serif;">
<div class="background mui-container-fluid mainDiv" style="background-image: url('<?php echo $DirPublic ?>/bkg.gif');background-size: 100%;">
	<div style="height: 5%;"></div>
	<div class="mui-panel mui-container-fluid" style="max-width: 800px;">
		<form method="post" enctype="application/json" class="mui-form">
			<legend>Register a User</legend>
			<div class="mui-divider"></div>
			<table class="mui-table">
				<tr><td>
					<span>You can register yourself here, which allows you to view news.</span>
				</td></tr>
				<?php if($error) { ?>
				<tr><td>
					<span style="font-family: 'Open Sans',sans-serif;color:red; animation: 500ms fadeIn;">
						<b>Registration Failed</b>.<br><em>The username already exists, or the passwords entered did not match(or it was too short). Please try again.</em>
					</span>
				</td></tr>
				<?php } ?>
				<tr><td>
					<div class="mui-textfield mui-textfield--float-label">
					<input type="text" name="name" id="name" required="true" class="mui--no-user-select">
					<label for="name">Username</label>
					</div>
						<span id="status2" style="color: red;" class="mui--text-caption">Username should begin with an '@', and contain only alphanumeric characters. @username123</span>
				</td></tr>
				<tr><td>
					<div class="mui-textfield mui-textfield--float-label">
					<input type="password" name="pass" id="pass" required="true" autocomplete="OFF" class="mui--no-user-select">
					<label for="pass">Password</label>
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
				<tr><td style="text-align: right;"><button type="submit" class="mui-btn mui-btn--primary">REGISTER</button></td></tr>
			</table>
		</form>
	</div>	
</div>	
</body>
</html>