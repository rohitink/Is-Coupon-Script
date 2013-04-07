<?php
include('../dbconnect.php');
$id = $_GET['id'];

$process = $connection->prepare("DELETE FROM coupon WHERE id = $id");
$process->execute(array());

$process2 = $connection->prepare("DELETE FROM coupon_meta WHERE post_id=".$id);
if(!$process2)
	print_r($connection->errorInfo());
$process2->execute(array());

?>