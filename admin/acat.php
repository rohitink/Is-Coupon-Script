<?php
//Approve category to show in Menubar
include('../dbconnect.php');
$id = $_GET['id'];
$database = $connection->prepare("UPDATE cats SET status = 1 WHERE cat_id=".$id);
$database->execute(array());