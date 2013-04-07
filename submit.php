<?php
include('header.php');



if (isset($_POST['code']))
	{
		if ( strlen($_POST['code']) == 0 || strlen($_POST['expirydate']) == 0 || strlen($_POST['description']) == 0 ) {
			echo "All Details About the Coupon Code are Must. Fill in all the fields.<br><small>Go Back via ur browser to fix the errors...</small>"; }
			else {
					require_once('dbconnect.php'); 
					$database = $connection->prepare("INSERT INTO coupon (coupon, expirydate, submittedby, lastused,description,url) values (:coupon, :expirydate, :submittedby, CURDATE(), :description, :url)");
					if(!$database)
						print_r($connection->errorInfo());
					else {	
						$database->execute(array(
							"coupon" => $_POST['code'],
							"expirydate" => $_POST['expirydate'],
							"submittedby" => $_POST['submittedby'],
							"description" => $_POST['description'],
							"url" => $_POST['website']
							));
					}
					//Updating Coupon Meta Table
					foreach ($_POST['checklist'] as $li)
					{
						$database = $connection->prepare("INSERT INTO coupon_meta (post_id, cat_id) values ((SELECT id FROM coupon WHERE coupon='".$_POST['code']."'), ".$li.")");
						if (!$database)
							print_r($connection->errorInfo());
						else {
							$database->execute(array());
						}
					}
					
					
					
				echo "<br><br>Coupon Submitted on ".date('D, Y-m-d H:i a')." Thanks.";	
			}
			
	} else {
?>
<div id="formarea">
<form id="submit" method="post" action="submit.php">
<table id="formtable">
<tr><td>Coupon:</td><td> <input type="text" name='code'></td></tr>
<tr><td>Description:</td><td> <textarea name="description"></textarea></td></tr>
<tr><td>Your Name:</td><td> <input type="text" name="submittedby"></td></tr>
<tr><td>Your Site URL:</td><td> <input type="url" name="website"></td></tr>
<tr><td>Expiry Date:</td><td> <input type="date" placeholder="dd/mm/yy" name="expirydate"></td></tr>
<tr><td>Category(s)</td><td> <? 
$pcat = $connection->prepare("SELECT * FROM cats");
$pcat->execute(array());
$cats = $pcat->fetchAll(PDO::FETCH_ASSOC);
foreach ($cats as $cat)
	echo "<input type='checkbox' name='checklist[]' value='".$cat['cat_id']."'>".$cat['name']."<br>"; 	
?></td>
</table>
<button type="submit" value="submit">Submit</button>
</form>
</div>
<?php } //end else - show form

include('sidebar.php');
include('footer.php'); ?>
