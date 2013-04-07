<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Finalize the Site</title>
</head>

<body>
<?php
include('../dbconnect.php');
$create = $connection->prepare("CREATE TABLE IF NOT EXISTS cats (cat_id int NOT NULL UNIQUE AUTO_INCREMENT, name varchar(60), `desc` text, status int DEFAULT 0)");
$create->execute(array());
if(!$create)
	echo $connection->errorInfo();
	
$create = $connection->prepare("CREATE TABLE IF NOT EXISTS coupon (id int NOT NULL AUTO_INCREMENT KEY, coupon text, lastused date, expirydate date, submittedby varchar(26), description text, url text, success int DEFAULT 0, fail int DEFAULT 0, status int DEFAULT 0)");
$create->execute(array());
if(!$create)
	echo $connection->errorInfo();	
	
$create = $connection->prepare("CREATE TABLE IF NOT EXISTS coupon_meta (post_id int, cat_id int)");
$create->execute(array());
if(!$create)
	echo $connection->errorInfo();

$create = $connection->prepare("CREATE TABLE IF NOT EXISTS options (title text, url text, homedesc text , metatitle text, metakeywords text, id int)");
$create->execute(array());
if(!$create)
	echo $connection->errorInfo();		

$insert = $connection->prepare("INSERT into options values('Coupon Script', 'http://urdomainname.com', 'Not Just Another Coupons Website', '', '', 1)");
$insert->execute(array());
echo "<h2>Done!</h2>";
echo "Script Installation Was Successful! Go To Your <a href='../index.php'>New Site.</a>"

?>
</body>
</html>
