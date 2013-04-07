<?php

	include('dbconnect.php');
	$id = $_GET['id'];
			
			$date = date('Y-m-d');
			$upvote = $connection->prepare("UPDATE coupon SET success = success+1, lastused=CURDATE() WHERE id = ".$id);
			$upvote->execute(array());
			$votes = $upvote->fetchAll(PDO::FETCH_ASSOC);
			setcookie("upvote".$id, "set");
			if ($_COOKIE["downvote".$id] == "set") {
				$downvote = $connection->prepare("UPDATE coupon SET fail = fail-1 WHERE id = $id ");
				$downvote->execute(array());
				$votes = $downvote->fetchAll(PDO::FETCH_ASSOC);
				setcookie("downvote".$id, NULL);
			}
			
?>