<?php
	$host="localhost";		// Host name
	$username="root";		// Mysql username
	$password="";			// Mysql password
	$db_name="npws";		// Database name
	$DirPublic="./public"	// public directory
?>

<?php 
	// CODE FOR ANONYMOUSLY PROFILING USERS FOR SERVING BETTER NEWS PREFERENCES...
	// leaving it in config.php, since this file is included EVERYWHERE ELSE

	$seed = 4294967296;	//some large number that can be used as seed, the larger the better
	$fingerprint = hash_hmac("sha256", mt_rand(-($seed-1),$seed), mt_rand(-($seed-1),$seed)); //pseudorandom value that can be used as fingerprint
	$cookie_name = "fpt";
	$novelty = time() + (86400 * 30);	//assuming the user as "fresh" if not using the platform for 'novelty' duration...
	if(!isset($_COOKIE[$cookie_name]))	//if fingerprint not already set,
		setcookie($cookie_name, $fingerprint, $novelty, "/"); //set new cookie with new fingerprint
	else if(isset($_COOKIE[$cookie_name]))
		setcookie($cookie_name, $_COOKIE[$cookie_name], $novelty, "/");	//otherwise, renew the validity of the cookie
	else
		die("Cookie error.");	//else, die a cookie death.
?>