<?php include 'config.php';	//includes the common configurations ?>
<?php
	session_start();	//starts the session module...

	if(!isset($_SESSION['name']))	die("You are not logged in to NPWS.<br><a href='./index.php'>Go to login.</a>"); //header("Location: ./index.php");	//redirect to login if user has no session

	$included = true;	//this flag will be used to check whether the pages are included into this drawer page or not

	//below are the whitelisted page parameters that will include the respective page into the drawer page
	$whitelist = array(
		'homepage' => 'homepage-in.php',
		'modpass'  => 'modpass-in.php',
	);
	$adminwhitelist = array(
		'post' => 'post-in.php',
		'register' => 'register-in.php',
		'edit' => 'edit-in.php',
	);
	
	if(isset($_GET) || isset($_GET['page'])) {
		if(array_key_exists($_GET['page'], $whitelist))	//will check for spurious entries to the page parameter(not in whitelist)
			$page = $whitelist[$_GET['page']];	//will get the page to be included from the whitelist
		else if(isset($_SESSION) && $_SESSION['admin'] && array_key_exists($_GET['page'], $adminwhitelist))	//these pages can only be accessed if admin
			$page = $adminwhitelist[$_GET['page']];
		else {
			$page = $whitelist['homepage'];	//give the user the homepage if they are trying to access pages outside this whitelist...
			//header("Location: ./deauth.php");
		}
	} else $page = $whitelist['homepage'];	//if the get parameter is not set, assume homepage.php

?>
<!DOCTYPE html>
<html>
<head>
	<title>NPWS | Homepage</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="<?php echo $DirPublic ?>/mui.min.css" rel="stylesheet" type="text/css" />
	<script src="<?php echo $DirPublic ?>/mui.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo $DirPublic ?>/style.css">
	<script type="text/javascript" src="<?php echo $DirPublic ?>/jquery.min.js"></script>
	<script type="text/javascript">
		jQuery(function($) {
			var $bodyEl = $('body'),
			$sidedrawerEl = $('#sidedrawer');

			function showSidedrawer() {
				// show overlay
				var options = {
					onclose: function() {
						$sidedrawerEl
						.removeClass('active')
						.appendTo(document.body);
						$("body").removeClass('mui-scroll-lock');
					}
				};

			var $overlayEl = $(mui.overlay('on', options));
			$bodyEl.addClass('mui-scroll-lock');
				// show element
				$sidedrawerEl.appendTo($overlayEl);
				setTimeout(function() {
				$sidedrawerEl.addClass('active');
				}, 20);
			}

			var $titleEls = $('strong', $sidedrawerEl);

			$titleEls
			.next()
			.hide();
			$titleEls.on('click', function() {
			$(this).next().slideToggle(200);
			});	

			function hideSidedrawer() {
				$bodyEl.toggleClass('hide-sidedrawer');
			}

			$('.js-show-sidedrawer').on('click', showSidedrawer);
			$('.js-hide-sidedrawer').on('click', hideSidedrawer);
		});	//script for drawer...
	</script>
</head>
<body style="font-family: 'Calibri',sans-serif;">
<header id="header" class="header-shadow">
	<div class="mui-appbar mui--appbar-height">
		<table class="mui-appbar mui--appbar-height">
			<tr><td><a class="sidedrawer-toggle mui--visible-xs-inline-block mui--visible-sm-inline-block js-show-sidedrawer"><i class="fa fa-bars"></i></a>
			<a class="sidedrawer-toggle mui--hidden-xs mui--hidden-sm js-hide-sidedrawer"><i class="fa fa-bars"></i></a>
			<span class="mui--text-headline appbar-brand"><?php echo isset($_GET['page'])?$_GET['page']:"Homepage" ?></span></td></tr>
			</td>
			</tr>
		</table>
	</div>
</header>
<div id="sidedrawer" class="mui--no-user-select">
	<table id="sidedrawer-brand" class="mui--appbar-height">
	<tr><td><span class="mui--text-headline">NPWS</span></td></tr>
	</table>
	<!--<div class="mui-divider"></div>-->
	<ul>
		<li>
			<div class="mui-dropdown">
				<button class="mui-btn mui-btn--flat" data-mui-toggle="dropdown">
					<b><span>Welcome! <?php echo $_SESSION['name'] ?></span></b></button>
				<ul class="mui-dropdown__menu mui-dropdown__menu--right">
					<?php 
						if(isset($_SESSION['admin']) && $_SESSION['admin'])	{//if user is admin
					?>
						<li><a href="./drawer.php?page=post" title="Create an article">Write Article</a></li>
						<li><a href="./drawer.php?page=register" title="Register administrator">Register Admin</a></li>
					<?php } ?>
					<li><a href="./drawer.php?page=modpass" title="Logout">Change Password</a></li>
					<li><a href="./logout.php" title="Logout">Logout</a></li>
				</ul>
			</div></li>
			<?php if(isset($_SESSION['admin']) && $_SESSION['admin'])	{//if user is admin ?>
				<li><a href="./drawer.php?page=register" title="Register administrator"><strong>Register Admin</strong></a></li>
				<li><a href="./drawer.php?page=post" title="Create an article"><strong>Write Article</strong></a></li>
			<?php } ?>
			<li><a href="./drawer.php?page=homepage" title="Go to homepage"><strong>Homepage</strong></a></li>
			<li><a href="./drawer.php?page=modpass" title="Logout"><strong>Change Password</strong></a></li>
			<li><a href="./logout.php" title="Logout"><strong>Logout</strong></a></li>
	</ul>
</div>
<div id="appbar-placeholder" class="mui--appbar-height"></div>
<div style="height: 10%;"></div>
<div id="content-wrapper" class="mui-container-fluid">
	<?php		require $page;	?>
</div>
</body>
</html>