<?php
include('../dbconnect.php');
$id = $_GET['id'];

$process = $connection->prepare("UPDATE coupon SET status = 1 where id = $id");
$process->execute(array());

?>