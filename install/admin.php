<?php
if(isset($_POST['submit']))
{
	include('../functions.php');
	$create = $connection->prepare("CREATE TABLE IF NOT EXISTS admin (username varchar(30) UNIQUE, password varchar(90), email 		varchar(100))");
	$create->execute(array());
	if(!$create)
		echo $connection->errorInfo();
	
	$p = generateSecureHash($_POST['password'], 12324234);
	$e = $_POST['mail'];	
	$insert = $connection->prepare("INSERT INTO admin (username,password,email) values('admin','".$p."','".$e."')");
	$insert->execute(Array());	
	if(!$insert)
		echo $connection->errorInfo();
	header("Location: finalize.php");	
}

?><!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Create Admin Account</title>
</head>
<body>
<h2>
Create an Admin Account - Step 2
</h2>

<form name="adminac" method="post" action="admin.php">
Username: <input type="text" value='admin' disabled name="usernameshow"> - You Can Not change username.<br>
<input type="hidden" value='admin' disabled name="username"><br>
Password: <input type="password" name="password"><br>
E-Mail Id: <input type="email" name="mail"><br>
<input type="submit" name='submit' value="Create!">
</form>

</body>
</html>
