<?php include 'config.php';	//includes the common configurations ?>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="<?php echo $DirPublic ?>/mui.min.css" rel="stylesheet" type="text/css" />
	<script src="<?php echo $DirPublic ?>/mui.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo $DirPublic ?>/style.css">
	<script type="text/javascript" src="<?php echo $DirPublic ?>/jquery.min.js"></script>
</head>
<body style="font-family: 'Calibri',sans-serif;">

<?php
	session_start();	//session_start has to be everywhere!!
	
	if(isset($_SESSION['name'])) {
		header("Location: ./drawer.php?page=homepage");	//echo 'success: '. $_COOKIE['PHPSESSID'];
?>
<br><a href="./logout.php">LOGOUT(TEST)</a>
<?php	} else { ?>

	<div class="background mui-container-fluid">
	<div style="height: 8%;"></div>
	<div class="mui-panel mui-container-fluid" style="max-width: 800px;" >
		<table class="mui-table">
			<tr><td>	
				<h1 class="mui--no-user-select" title="Session failure.">
					Your login failed.
				</h1>
			</td></tr>
			<tr><td align="left">	
				<p class="mui--text-headline">
					This could be due to the following reasons.<br>
					<ul>
						<li>Cookies are disabled on your browser. Please enable cookies on your system to continue using this service.</li>
						<li>Your session may have expired, or you were not logged in. Please <a href="./index.php">login</a> and try again.</li>
					</ul>
					<!--<br>Otherwise, <a href="./homepage.php">View as anonymous</a>.-->
				</p>
			</td></tr>
		</table>
	</div>
</div>
<div style="position:fixed; bottom: 20px; right: 20px;">&copy;Yash Diniz & Imamsab Bagwan 2018.</div>
</body>
<?php } ?>