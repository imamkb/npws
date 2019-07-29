<script>
	//script to validate the form on client-side...
	$(document).ready(function() {
		$("#status").hide();	//hide the error by default...
		$("#status2").hide();
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

<?php
	//session_start();
	
	if(!isset($included)) header("Location: ./drawer.php?page=homepage");	//if edit isn't specified, it will jump back to homepage //die("<a href='./drawer.php?page=homepage'>Redirect to homepage</a>"); //

	if(!isset($included) && (!isset($_SESSION['admin']) || !$_SESSION['admin']))	
		header("Location: ./deauth.php");	//redirect to login if user is not // admin die("<a href='./logout.php'>Logout.</a>"); //
	
	$tbl_name="news";	// Table name
	$error = false;	//flag is false by default

	// Connect to server and select database.
	mysql_connect("$host", "$username", "$password")or die("cannot connect");
	mysql_select_db("$db_name")or die("cannot select DB");

	if(isset($_POST['submit'])) {
		if(isset($_POST['category']) && isset($_POST['title']) && isset($_POST['description'])
		&& strlen($_POST['description'])<=450 
		&& strlen($_POST['title'])<=75 && isset($_POST['id'])) {
			$id = $_POST['id'];
			$title = rawurlencode($_POST['title']);
			$name = $_SESSION['name'];	//author of the post becomes owner of the session
			$desc = rawurlencode($_POST['description']);
			$cat = $_POST['category'];

			if($_SESSION['admin']==1) {	//if user is admin
				$sql = "UPDATE $tbl_name SET title='$title', author='$name', create_date=now(), description='$desc', category='$cat' where artid='$id'";
				$result = mysql_query($sql);
				if($result) {
					echo "Post modified.";
					echo "<script>location.assign('./drawer.php?page=homepage');</script>";
					//echo "<div class='mui--text-headline'><center>Post modified.";
					//die("<br><a href='./drawer.php?page=homepage'>Go to homepage.</a></center></div>"); //header("Location: ./drawer.php?page=homepage");
				} else $error = true;
			} else $error = true;
		} else $error = true;
	}
?>
<div class="mui-panel" style="width: 90%;">
<?php
		$id = isset($_GET['id'])?$_GET['id']:"";
		$sql = "SELECT * FROM $tbl_name where artid='$id'";
		$result = mysql_query($sql);
		if(mysql_num_rows($result)) {
			$row = mysql_fetch_array($result);
	?>
	<form method="post" enctype="multipart/form-data" class="mui-form">
		<legend>Create an article</legend>
		<input type="hidden" name="id" value="<?php echo $id; ?>">	<? //saves the values in the form of an ID in the form ?>
		<table class="mui-table" style="width: 100%;">
			<?php if($error) { ?>
			<tr><td>
				<span style="font-family: 'Open Sans',sans-serif;color:red; animation: 500ms fadeIn;">
					<b>Failed editing article.</b><br>
					<em>The data failed to save. Please try again.</em>
				</span>
			</td></tr>
			<?php } ?>
			<tr><td>
			<div class="mui-select">
				<select name="category" required="true" id="category">
				<?php
					$tbl_name = "categories";
					$sql = "SELECT * FROM $tbl_name";
					$result = mysql_query($sql);
					while ($cat = mysql_fetch_array($result)) {
				?>
				<option value="<?php echo $cat['cat']; ?>"  <?php echo ($row['category']==$cat['cat'])?"selected='true'":""; ?> > <?php echo $cat['cat']; ?> </option>
			<?php } ?>
				</select>
				<label for="category">Choose Category</label>
			</div></td></tr>
			<tr><td>
				<div class="mui-textfield mui-textfield--float-label">
				<input type="text" name="title" id="pass" required="true" maxlength="75" value="<?php echo rawurldecode($row['title']); ?>">
				<label for="pass">Title</label>
				</div>
			</td></tr>
			<tr><td>
				<div class="mui-textfield mui-textfield--float-label">
				<textarea name="description" style="font-size: 15px;" maxlength="300" required="true"><?php echo rawurldecode($row['description']); ?></textarea>
				<label for="description">Give Description</label>
				</div>
			</td></tr>
			<tr><td style="text-align: right;"><button type="submit" name="submit" class="mui-btn mui-btn--primary">MAKE POST</button></td></tr>
		</table>
	</form>
<?php } else die("Post not found. <a href='./drawer.php?page=homepage'>Go to homepage.</a>"); //header("Location: ./drawer.php?page=homepage"); ?>
</div>