<?php
$dbusername = "DB_USERNAME";
$dbpassword = "DB_PASSWORD";
$connection = new PDO('mysql:host=MYSQL_HOST;dbname=DATABASE_NAME_HERE', $dbusername, $dbpassword);
$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
?>