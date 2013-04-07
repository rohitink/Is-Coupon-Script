<?php
//Hide category from Menubar
include('../dbconnect.php');
$id = $_GET['id'];
$database = $connection->prepare("UPDATE cats SET status = 0 WHERE cat_id=".$id);
$database->execute(array());