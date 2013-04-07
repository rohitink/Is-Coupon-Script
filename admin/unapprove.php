<?php
include('../dbconnect.php');
$id = $_GET['id'];

$process = $connection->prepare("UPDATE coupon SET status = 0 where id = $id");
$process->execute(array());

?>