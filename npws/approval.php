<?php include 'config.php'; ?>
<?php 
	if(isset($_POST['art'])) {
		$article = $_POST['art'];	//the article to approve
		// Connect to server and select database.
		mysql_connect("$host", "$username", "$password")or die("cannot connect");
		mysql_select_db("$db_name")or die("cannot select DB");

		//get the news article
		$tbl_name = "news";
		$sql = "SELECT `category` FROM $tbl_name where `artid`=$article";
		$result = mysql_query($sql);
		if($result && isset($_COOKIE['fpt']))	{ //result achieved, and fingerprint exists
			$category = mysql_fetch_object($result)->category;
			$session = $_COOKIE['fpt'];
			mysql_query("CALL `insert_user_interest`('$category','$session')");	//insert the user's interests
			echo "Success";
		} else die("Post not found or Cookie failure.");
	}
?>