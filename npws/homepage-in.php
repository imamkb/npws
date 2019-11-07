<?php include 'config.php';	//includes the common configurations ?>
<script type="text/javascript">
		function activateModal(title, author, date, desc, img_url, artid) {
			$.post("./approval.php",{art: artid}, function(data, status) {	//display modal after server approval
				console.log(data);
				var modal = document.createElement("div");
				var des = decodeURIComponent(desc).replace(/\n/gi,"<br>");
				modal.className = "mui-panel";
				$(modal).css({padding:"20px" , "max-width":"500px" , 
					margin:"100px auto" , transition:".5s ease" , "border-radius": "20px" });
				var article = document.createElement("article");
				$(article).html("<center>" +
					"<img src='" + img_url + "' style='max-width:100%; max-height:300px;'>"+
					"</center>" +
					"<span class='mui--text-headline'>" + decodeURIComponent(title) + "</span><br>" + 
					"<span class='mui--text-caption' title=" + date + ">" + date + "</span> |" +
					"<span class='mui--text-subhead'>" + author + "</span>" +
					"<div class='mui-divider' style='margin: 10px;'></div>" +
					"<p class='mui--text-subhead'>" + des + "</p>");
				modal.appendChild(article);
				mui.overlay('on',modal);
			});
		}
</script>
<div class="mui-col-sm-10 mui-col-xs-offset-1 mui--no-user-select">
	<span class="mui--text-headline">Headlines</span>
	<div class="mui-dropdown" style="float:right;">
		<button class="mui-btn mui-btn--flat" data-mui-toggle="dropdown" style="font-size:20px;">
			<i class="fa fa-bars"></i>
		</button>
		<ul class="mui-dropdown__menu mui-dropdown__menu--right">
				<li><a href="./drawer.php?page=homepage&up=<?php 
					echo ((isset($_GET['up']) && $_GET['up']==1)?'0':'1') ?>">
					<?php 
						echo "Sort by ".((isset($_GET['up']) && $_GET['up']==1)?"Descending":"Ascending") 
					?>
				</a></li>
				<?php
					// Connect to server and select database.
					mysql_connect("$host", "$username", "$password")or die("cannot connect");
					mysql_select_db("$db_name")or die("cannot select DB");

					$tbl_name = "categories";
					$sql = "SELECT * FROM $tbl_name";
					$result = mysql_query($sql);
					if($result && mysql_num_rows($result))
					while ($cat = mysql_fetch_array($result)) {
				?>
					<li>
					<a href="./drawer.php?page=homepage&cat=<?php echo $cat['cat'] ?>">
					<?php 
						echo "Sort by ".$cat['cat']; 
					?></a>
					</li>
				<?php } ?>
		</ul>
	</div>

</div>
<div class="mui-row">
<?php
	if(!isset($included)) header("Location: ./drawer.php?page=homepage");//die("<a href='./drawer.php?page=homepage'>Go to homepage</a>");//

	if(!isset($_SESSION['name'])) die("You are not logged in. <a href='./index.php'>Go to login</a>");//header("Location: ./index.php");	//redirect to login if user has no session

	// Connect to server and select database.
	mysql_connect("$host", "$username", "$password")or die("cannot connect");
	mysql_select_db("$db_name")or die("cannot select DB");

	$tbl_name = "news";
	$cat = isset($_GET['cat'])?$_GET['cat']:"";
	// $sql = "SELECT DATE_FORMAT(create_date, '%d %b %y') as mod_date, artid, description, title, author, file_url ,category FROM $tbl_name ".(isset($_GET['cat'])?"where category='$cat' ":" ")."ORDER BY artid ".((isset($_GET['up']) && $_GET['up']==1)?"asc":"desc");
	if(isset($_COOKIE['fpt'])) $fingerprint = $_COOKIE['fpt'];
	else $fingerprint = 0;	//otherwise cookie not set
	$sql = "select * from ((select temp.`artid`,
		 		temp.`title`,
		 		temp.`author`,
		 		DATE_FORMAT(temp.create_date, '%d %b %y') as mod_date,
		 		temp.`description`,
		 		temp.`category`,
		 		temp.`file_url`,
		 		temp.`count`	-- the posts will be sorted based on interest
		 from (select @fingpt:='$fingerprint') t , news_temp temp)
		UNION
		(select `artid`,
		 		`title`,
		 		`author`,
		 		DATE_FORMAT(`create_date`, '%d %b %y') as `mod_date`,
		 		`description`,
		 		`category`,
		 		`file_url`,
		 		@`count`:=0 `count`	-- the remaining posts with 0 interest are shown next
		 from news where artid not in
		(select temp.artid from (select @fingpt:='$fingerprint') t , news_temp temp))) temp1
		".(isset($_GET['cat'])?"where category='$cat' ":" ")."order by temp1.`count` ".((isset($_GET['up']) && $_GET['up']==1)?"asc":"desc");
	$result = mysql_query($sql);
	if($result && mysql_num_rows($result))	{
		while ($news = mysql_fetch_array($result)) {
?>
<div class="mui-panel mui-col-sm-10 mui-col-sm-offset-1 mui--no-user-select" id="<?php echo $news['artid']; ?>" style="text-align: left; border-radius: 30px;">
	<?php if($_SESSION['admin']) { ?>
		<div class="mui-dropdown" style="float:right;">
			<button class="mui-btn mui-btn--flat" data-mui-toggle="dropdown" style="border-radius:69px; font-size:20px;"><i class="fa fa-ellipsis-v"></i></button>
			<ul class="mui-dropdown__menu mui-dropdown__menu--right">
				<li><a href="./drawer.php?page=edit&id=<?php echo $news['artid']; ?>">Edit post</a></li>
				<li><a href="./delete.php?id=<?php echo $news['artid']; ?>">Delete post</a></li>
			</ul>
		</div>
	<?php } ?>
	<span class="mui--text-headline"><?php echo rawurldecode($news['title']); ?></span><br>
	<span class="mui--text-caption" title="<?php echo $news['mod_date']; ?>"><?php echo $news['mod_date'] ?></span> | 
	<span class="mui--text-caption"><?php echo $news['category'] ?></span> | 
	<span class="mui--text-subhead"><?php echo $news['author'] ?></span>
	<div class="mui-divider" style="margin: 10px;"></div>
	<p class="mui--text-subhead"><?php echo substr(rawurldecode($news['description']), 0, 150)."..." ?></p>
	<div>
	<center>
		<img src="<?php echo $news['file_url'] ?>" style="max-width:100%; max-height:300px; border:solid thin gray; border-radius: 20px;">
	</center>
	</div>
	<div class="mui-divider"></div>
	<center><a href="#<?php echo $news['artid']; ?>" onclick="activateModal('<?php echo $news['title']; ?>','<?php echo $news['author'] ?>','<?php echo $news['mod_date']; ?>','<?php echo $news['description'] ?>','<?php echo $news['file_url'] ?>','<?php echo $news['artid'] ?>')" style="margin:10px;">Read more</a></center>
</div>
<?php 
	} } else { ?>
	<div class="mui-panel mui-col-sm-10 mui-col-sm-offset-1" id="fail">
		<p class="mui--text-headline">
			No articles to show...
		</p>
	</div>
<?php } ?>	
</div>