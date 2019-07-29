<?php include 'config.php';	//includes the common configurations ?>
<?php
	session_start();	//starts the session module...

	if(!isset($_SESSION['name']))	die("You are not logged in to NPWS.<br><a href='./index.php'>Go to login.</a>"); //header("Location: ./index.php");	//redirect to login if user has no session

	$included = true;	//this variable will be used to check whether the pages are included into the page or not

	if(!isset($_GET) || !isset($_GET['page'])) {
		$page = 'homepage-in.php';
	}
	else if($_GET['page'] === 'homepage') {
		$page = 'homepage-in.php';
	}
	else if($_GET['page'] === 'modpass') {
		$page = 'modpass-in.php';
	}
	else if(isset($_SESSION) && $_SESSION['admin']) {
		if($_GET['page'] === 'post') {
			$page = 'post-in.php';
		}
		else if($_GET['page'] === 'register') {
			$page = 'register-in.php';
		}
		else if($_GET['page'] === 'edit') {
			$page = 'edit-in.php';
		}
	} else header("Location: ./deauth.php");

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
		/*$(document).ready(function() {
			$(".dp .overlay").click(function() {
				activateModal();
			});
		});
		function activateModal() {
			var modal = document.createElement("div");
			modal.className = "mui-panel";
			$(modal).css({padding:"20px" , "max-width":"500px" , margin:"100px auto" , transition:".5s ease"});
			var form = document.createElement("form");
			form.action = "/dp" , form.method = "post" , form.enctype = "multipart/form-data" , form.className = "mui-form";
			$(form).html("<p>Change your display picture:</p> \
				<center> \
				<div class='mui-textfield'> \
				<input type='file' name='Picture' accept='image/*' style='font-size:12px; width:70%;'> \
				</div> \
				</center> \
				<div style='height:20px;'></div> \
				<div style='text-align:right;'> \
				<button type='submit' class='mui-btn mui-btn--primary'>Upload</button> \
				</div>");
			modal.appendChild(form);
			mui.overlay('on',modal);
		}*/
	</script>
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