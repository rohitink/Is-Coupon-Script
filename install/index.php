<?php
if(isset($_POST['submit']))
{
	if( ($_POST['username'] != "") && ($_POST['name'] != "") && ($_POST['password'] != "") && ($_POST['host'] != ""))
	{
	//read the entire string
	$str = file_get_contents('../dbconnect.php');
	
	$c_text = $_POST['username'];
	$dummyText = "DB_USERNAME";
	$replaceWith = $c_text;
	$str = str_replace("$dummyText", "$replaceWith",$str); //Replace str and store it in $STR as well
	
	$c_text = $_POST['password'];
	$dummyText = "DB_PASSWORD";
	$replaceWith = $c_text;
	$str = str_replace("$dummyText", "$replaceWith",$str); //Replace str and store it in $STR as well
	
	$c_text = $_POST['host'];
	$dummyText = "MYSQL_HOST";
	$replaceWith = $c_text;
	$str = str_replace("$dummyText", "$replaceWith",$str); //Replace str and store it in $STR as well
	
	$c_text = $_POST['name'];
	$dummyText = "DATABASE_NAME_HERE";
	$replaceWith = $c_text;
	$str = str_replace("$dummyText", "$replaceWith",$str); //Replace str and store it in $STR as well
	
	//Update the Database File
	file_put_contents('../dbconnect.php', $str);
	header("Location: admin.php");
    }
}
?><!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Install CouponScript</title>
</head>
<body>
<h2>Install CouponScript - Step 1</h2>

<form id="dbdetails" name='db' action="index.php" method="post">
MySql Host: <input size="100" type="text" value='localhost' name='host'><br>
Database Name: <input size='100' type="text" placeholder="Name Of Your Database" name="name"><br>
Database Username: <input size='100' type="text" placeholder="A Valid DB User" name="username"><br>
Password: <input size='100' type="text" placeholder="Password for the Above User" name="password"><br>
<input type="submit" name="submit"> 
</form>
 

</body>
</html>
