<?php 
require_once('../functions.php');

$username = $_POST['username'];
$password = generateSecureHash($_POST['password'],12324234);

$database = $connection->prepare("SELECT * FROM admin WHERE username = 'admin' AND password = '".$password."'");
if(!$database)
	print_r($connection->errorInfo());	
$database->execute(array());
$result = $database->fetch(PDO::FETCH_ASSOC);

if (count($result) == 3)
{
	setcookie("admin_coupon", TRUE, time()+60*60*24*30, '/'); 
	echo "success";
}

//Do not terminate to avoid accidental output