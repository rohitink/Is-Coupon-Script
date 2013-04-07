<?php
include('../dbconnect.php');
$newcat = $_POST['newcat'];

$database = $connection->prepare("INSERT into cats (name, status) values ('".$newcat."', 1)");
$database->execute(array());

//avoid accidental output