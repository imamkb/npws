<?php
	if(!isset($included)) header("Location: ./drawer.php?page=post"); //die("<a href='./drawer.php?page=post'>Go to post page.</a>"); //
	//session_start();
	
	if(!isset($included) && (!isset($_SESSION['admin']) || !$_SESSION['admin']))	
		header("Location: ./deauth.php");	//redirect to login if user is not admin

	$tbl_name="news";	// Table name
	$error = false;	//flag is false by default

	// Connect to server and select database.
	mysql_connect("$host", "$username", "$password")or die("cannot connect");
	mysql_select_db("$db_name")or die("cannot select DB");


	if(isset($_POST['submit'])) {
		if(isset($_POST['category']) && isset($_POST['title']) && isset($_POST['description'])
		&& strlen($_POST['description'])<=450 
		&& strlen($_POST['title'])<=75
		&& isset($_FILES['File'])) {
			$title = rawurlencode($_POST['title']);
			$name = $_SESSION['name'];	//author of the post is owner of the session
			$desc = rawurlencode($_POST['description']);
			$cat = $_POST['category'];
			
			//file upload check code
			$target_dir = "uploads/";
			$uploadOk = 0;	//by default, uploadOk is false
			$file_size = 5000000;	//currently max file size is 5MB
			$imageFileType = strtolower(pathinfo($_FILES['File']['name'],PATHINFO_EXTENSION));	//saves file extension
			$target_file = $target_dir . basename($_SESSION['name'].'-'.microtime().'.'.$imageFileType);
			// Check if image file is a actual image or fake image
			$check = getimagesize($_FILES['File']["tmp_name"]);
			if($check !== false) $uploadOk = 1;	//File is an image... check with $check["mime"]
			else $uploadOk = 0;	//echo "File is not an image.";
			// Check if file already exists
			if (file_exists($target_file)) $uploadOk = 0; //Sorry, file already exists
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" &&
				$imageFileType != "jpeg" && $imageFileType != "gif" ) $uploadOk = 0;	//Sorry, only JPG, JPEG, PNG & GIF files are allowed
			if (!move_uploaded_file($_FILES["File"]["tmp_name"], $target_file))	//this part actually uploads file!
				$uploadOk = 0; //Sorry, there was an error uploading your file

			if($uploadOk === 1 && $_SESSION['admin']==1) {	//if file is uploaded successfully AND user is admin
				$sql = "INSERT INTO $tbl_name(artid, title, author, create_date, description, category, file_url) VALUES (unix_timestamp(), '$title', '$name', now(), '$desc', '$cat', '$target_file')";
				$result = mysql_query($sql);
				if($result) {
					echo "<div class='mui--text-headline'><center>Post created.";
					echo "<script>location.assign('./drawer.php?page=homepage');</script>";
					//die("<br><a href='./drawer.php?page=homepage'>Go to homepage.</a></center></div>"); //header("Location: ./drawer.php?page=homepage");	//TODO: change this to ./drawer.php?page=homepage
				} else $error = true;
			} else $error = true;
		} else $error = true;
	}

?>
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

<div class="mui-panel" style="width: 90%;">
	<form method="post" enctype="multipart/form-data" class="mui-form">
		<legend>Create an article</legend>
		<table class="mui-table" style="width: 100%;">
			<?php if($error) { ?>
			<tr><td>
				<span style="font-family: 'Open Sans',sans-serif;color:red; animation: 500ms fadeIn;">
					<b>Failed creating article.</b><br><em>The data failed to save. Please try again.</em>
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
				<option value="<?php echo $cat['cat']?>"><?php echo $cat['cat']?></option>
			<?php } ?>
				</select>
				<label for="category">Choose Category</label>
			</div></td></tr>
			<tr><td>
			<div class="mui-textfield">
				<input type="file" name="File" style="font-size: 13px;" required="true" >
				<label for="File">News Photo <i class="fa fa-file"></i></label>
			</div></td></tr>
			<tr><td>
				<div class="mui-textfield mui-textfield--float-label">
				<input type="text" name="title" id="pass" required="true" maxlength="75">
				<label for="pass">Title</label>
				</div>
			</td></tr>
			<tr><td>
				<div class="mui-textfield mui-textfield--float-label">
				<textarea name="description" style="font-size: 15px;" maxlength="300" required="true"></textarea>
				<label for="description">Give Description</label>
				</div>
			</td></tr>
			<tr><td style="text-align: right;"><button type="submit" name="submit" class="mui-btn mui-btn--primary">MAKE POST</button></td></tr>
		</table>
	</form>
</div>