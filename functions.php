<?php 
session_start();
include('dbconnect.php');

$options = $connection->prepare("SELECT * FROM options WHERE id = 1");
if($options) {
	$options->execute(array());
	$resultX = $options->fetch(PDO::FETCH_ASSOC); //This Returns Site Title, and Meta Details for Global use.
}
function get_page_title() {
	
	return "Browse Latest GoDaddy Coupon Codes";	
	
}

function get_seo_title() 
{
	global $resultX;
	global $connection;
	if(isset($_GET['category']))
	{
	$cat = $_GET['category'];
		$catname = $connection->prepare("SELECT * from cats WHERE cat_id=".$cat);
		$catname->execute(array());
		$ret_cat_name = $catname->fetch(PDO::FETCH_ASSOC);
	}
		
	if ((isset($_GET['page_no'])) && (isset($_GET['category']))) 
	{
		
		
		return $ret_cat_name['name']." | Page ".$_GET['page_no']." | ".$resultX['title'];
	}
	else if(isset($_GET['category']))
	{
		return $ret_cat_name['name']." | ".$resultX['title'];
	}
	else if(isset($_GET['page_no'])) 
		{
			return "Page ".$_GET['page_no']." | ".$resultX['title'];
		}
	else
		return $resultX['title'];
}
function show_categories()
{
	global $connection;
	//include('dbconnect.php');
	$database = $connection->prepare("SELECT * FROM cats WHERE status=1");
	$database->execute(array());
	$fetched_cats = $database->fetchAll(PDO::FETCH_ASSOC);
	foreach($fetched_cats as $fc)
	{
		echo "<a href='index.php?category=".$fc['cat_id']."' title='".$fc['name']."'>".$fc['name']."</a>";	
	} 
}
function log_in_form(){
	echo "<form id='adminlogin' method='post' action='index.php'>";
	echo "<input type='text' name='username' placeholder='Username'><br>";
	echo "<input type='password' name='password' placeholder='password'><br>";
	echo "<input type='submit' name='adminLogInSubmit'></form><br>";
	echo "<a id='forgot' href='forgot.php'>Forgot Password?</a>";
	echo "<form method='post' id='forgot'><input name='mail' type='text' placeholder='Email Here'><input type='submit' name='forgotsubmit'></form>";
}
function generateSecureHash($enteredPassword, $username) //Generate a Salted Secured Hash, With Time Delay
{
		$salt = substr( sha1($username), 0, 22 );
		return crypt($enteredPassword, '$2a$10$'.$salt); //BLOWFISH Algorithm
}
?>