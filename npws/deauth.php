<!-- This page is used as a banner to inform unauthorised users about their actions. -->
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
	<div class="background mui-container-fluid">
	<div style="height: 8%;"></div>
	<div class="mui-panel mui-container-fluid" style="max-width: 800px;" >
		<table class="mui-table">
			<tr><td>	
				<h1 class="mui--no-user-select" title="Session failure.">
					(403)Unauthorised.
				</h1>
			</td></tr>
			<tr><td align="left">	
				<p class="mui--text-headline">
					We apologize for the inconvenience, but you do not have the proper permissions to perform this action. Try: <br>
					<ul>
						<li>Your session may have expired, or you were not logged in. Please <a href="./logout.php">login</a> and try again.</li>
					</ul>
					<!--<br>Otherwise, <a href="./homepage.php">View as anonymous</a>.-->
				</p>
			</td></tr>
		</table>
	</div>
</div>
<div style="position:fixed; bottom: 20px; right: 20px;">&copy;Yash Diniz & Imamsab Bagwan 2018.</div>
</body>