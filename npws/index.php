<?php include 'config.php';	//includes the common configurations ?>
<!DOCTYPE html>
<html>
<head>
	<title>NPWS | News Portal Login</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="<?php echo $DirPublic ?>/mui.min.css" rel="stylesheet" type="text/css" />
	<script src="<?php echo $DirPublic ?>/mui.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo $DirPublic ?>/style.css">
	<script type="text/javascript" src="<?php echo $DirPublic ?>/jquery.min.js"></script>

	<?php
		session_start();

		$tbl_name="accounts";	// Table name
		$error = false;			// error flag(will turn true on invalid user logins)
		
		// Connect to server and select database.
		mysql_connect("$host", "$username", "$password")or die("cannot connect");
		mysql_select_db("$db_name")or die("cannot select DB");

		if(isset($_SESSION['name'])) header("Location: ./check.php");	//if user is already logged in, redirect them to the
																	//cookie check page (which checks if cookies are disabled or not)

		else if(isset($_POST['name']) && isset($_POST['pass'])) {	//if username and password are fed
			$name = $_POST['name'];
			$pass = $_POST['pass'];
			if(preg_match("/@\w+/i", $name)) {		//if username follows proper format... ie "@username123"
				$hash = hash_hmac("sha256",$pass,$pass);	//hashes the password using hmac(trivial approach)

				$sql="SELECT * FROM $tbl_name WHERE name='$name' and pass='$hash'";
				$result=mysql_query($sql);	//above SQL query executes and provides a matching result

				if($result && mysql_num_rows($result)==1) {	//if result has a value, and if number of matching rows is exactly 1
					$res = mysql_fetch_array($result);

					$sql="UPDATE $tbl_name SET last_login=now() where pass='$hash' and name='$name'";
					$result=mysql_query($sql);	//above SQL query executes and provides a matching result
					if($result) {
						//print_r($_SESSION);

						$_SESSION['name'] = $name;
						$_SESSION['time'] = microtime();	//saves session details with unix time
						$_SESSION['admin'] = $res['admin'];	//saves admin flag

						header("Location: ./check.php");	//redirects to check session after session creation
						
					} else $error=true;
				} else $error=true;	//invalid login
			} else $error=true;		//invalid login
		}
	?>

	<style type="text/css">
		.line , .logo , #name , #pass , #submit {
			animation: 600ms fadeIn linear;
		}
		.line {
			animation: 1s slideIn ease;
		}
		.mainDiv{
			background-image:url("<?php echo $DirPublic ?>/bkg.gif");
			background-size: 100%;
		}
	</style>
</head>
<body class="login">
<div class="background mui-container-fluid mainDiv">
	<div style="height: 8%;"></div>
		<!--<div class="mui--hidden-xs mui--hidden-sm mui-col-sm-4" style="background-color: rgba(255,255,255,0.4);">
			<img src="<?php echo $DirPublic ?>/logo.png">
		</div>-->
		<div class="mui-panel mui-container-fluid" style="max-width: 800px;">
			<h1 class="logo mui--no-user-select" title="NPWS - News Portal Website SE" style="font-family: 'Calibri','Open Sans';font-weight: 300;text-align: left;margin-bottom: 30px;">
				<i class="fa fa-newspaper-o"></i> <span class="N">N</span><span class="P">P</span><span class="W">W</span><span class="S">S</span>-SE
			</h1>
			
			<div class="mui-divider"></div>
			<?php if($error) { ?>
				<span id="status" style="font-family: 'Open Sans',sans-serif;color:red; animation: 500ms fadeIn;">
					<b>Login Failed</b>.<br><em>The username or password is incorrect. Please try again.</em>
				</span>
			<?php } ?>

			<form method="POST" enctype="application/json" class="mui-form">
				<table class="mui-table">
					<tr>
						<td><div class="mui-textfield mui-textfield--float-label">
						<input type="text" name="name" id="name" required="true">
						<label for="name">Username</label>
						</div></td>
					</tr>
					<tr>
						<td><div class="mui-textfield mui-textfield--float-label">
						<input type="password" name="pass" id="pass" required="true" autocomplete="OFF" class="mui--no-user-select">
						<label for="pass">Password</label>
						</div></td>
					</tr>
					<tr><td align="right">
						<span class="mui--text-caption mui--no-user-select">New to NPWS? <a href="./register.php">Create an account!</a></span>
					</td></tr>
					<tr><td style="text-align: right;"><button type="submit" name="submit" class="mui-btn mui-btn--primary">LOGIN</button></td></tr>
				</table>
			</form>
		</div>
	</div>
</body>
</html>