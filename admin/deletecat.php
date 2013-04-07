<?php
include('../dbconnect.php');
$dcat = $_POST['dcat'];


$database = $connection->prepare("SELECT * FROM cats WHERE name = '".$dcat."'");
$database->execute(array());
$r = $database->fetch(PDO::FETCH_ASSOC);
$id = $r['cat_id'];

$database = $connection->prepare("DELETE FROM cats WHERE name = '".$dcat."'");
$database->execute(array());

$database = $connection->prepare("DELETE FROM coupon_meta WHERE cat_id = ".$id);
$database->execute(array());

//avoid accidental output