<? 
//ADMIN EDIT FILE
include('../functions.php');
$var = date('Y-m-d');
?>

<!Doctype HTML>
<head>
<title>CouponScript Admin Section</title>
<script src="../js/jquery-1.8.2.min.js"></script>
<style>@import url(style.css); </style>
</head>
<body>
<div id="main">
<?
//Part of Code to be Processed When the User is Logged In
if (isset($_COOKIE['admin_coupon']))
{
	echo "<h2>Administration Panel</h2>";
	?>
    <div class='toplinks'>
    <a href="index.php?tab=pending">Pending Coupons</a>	
    <a href="index.php?tab=navigation">Navigation Menu</a>	
    <a href="index.php?tab=categories">Categories</a>
    <a href="index.php?tab=options">Site Options</a>
    <a class='logout' href="logout.php">Log Out</a><br><br>
    </div>
    <div id='main_admin'>	
    
    <? 
	//Check is the form was previous submitted
	if (isset($_POST['code']))
	{
		$id = $_REQUEST['pid'];
		if ( strlen($_POST['code']) == 0 || strlen($_POST['expirydate']) == 0 || strlen($_POST['description']) == 0 ) {
			echo "All Details About the Coupon Code are Must. Fill in all the fields.<br><small>Go Back via ur browser to fix the errors...</small>"; }
			else {
					//require_once('dbconnect.php'); 
					$database = $connection->prepare("UPDATE coupon SET coupon = '".$_POST['code']."', expirydate = '".$_POST['expirydate']."' , submittedby = '".$_POST['submittedby']."', lastused = '".$var."', description = '".$_POST['description']."' , url= '".$_POST['website']."' WHERE id = ".$id);
					
					if(!$database)
						print_r($connection->errorInfo());
					else {	
						$database->execute(array());
						
					}
					//Updating Coupon Meta Table
					$database = $connection->prepare("DELETE FROM coupon_meta WHERE post_id=".$id);
					$database->execute(array());
					foreach ($_POST['checklist'] as $li)
					{
						$database = $connection->prepare("INSERT INTO coupon_meta (post_id, cat_id) values ((SELECT id FROM coupon WHERE coupon='".$_POST['code']."'), ".$li.")");
						if (!$database)
							print_r($connection->errorInfo());
						else {
							$database->execute(array());
							
						}
					}
					echo "Coupon Updated.";
					?>
                    <script>
					$(document).ready( function() {
						
						$(location).attr('href','edit.php?id=<?=$id?>');
						alert("Coupon Was Updated");
					});
					</script>
                    <?
			}
	}
	else
	{
		//Show the code which involves editing of the coupon
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 1;
		$coupon = $connection->prepare("SELECT * FROM coupon WHERE id = $id");
		$coupon->execute(array());
		$result = $coupon->fetch(PDO::FETCH_ASSOC);
		?>
		<form id="submit" name='submit' method="post" action="edit.php">
        <input type="hidden" name='pid' value='<?=$id?>'>
		<table id="formtable">
		<tr><td>Coupon:</td><td> <input type="text" value='<?=$result['coupon']?>' name='code'></td></tr>
		<tr><td>Description:</td><td> <textarea name="description"><?=$result['description']?></textarea></td></tr>
		<tr><td>Name:</td><td> <input type="text" value='<?=$result['submittedby']?>' name="submittedby"></td></tr>
		<tr><td>Site URL:</td><td> <input type="url" value='<?=$result['url']?>' name="website"></td></tr>
		<tr><td>Expiry Date:</td><td> <input value='<?=$result['expirydate']?>' type="date" placeholder="dd/mm/yy" name="expirydate"></td></tr>
		<tr><td>Category(s)</td><td> <? 
		$pcat = $connection->prepare("SELECT * FROM cats, coupon_meta WHERE coupon_meta.post_id = $id AND coupon_meta.cat_id = cats.cat_id");
		$pcat->execute(array());
	
		$cats = $pcat->fetchAll(PDO::FETCH_ASSOC);
		foreach ($cats as $cat)
			echo "<input checked type='checkbox' name='checklist[]' value='".$cat['cat_id']."'>".$cat['name']."<br>"; 	
		$pcat = $connection->prepare("SELECT * 
	FROM cats
	WHERE cats.name NOT 
	IN (
	
	SELECT DISTINCT cats.name
	FROM cats, coupon_meta
	WHERE coupon_meta.cat_id = cats.cat_id
	AND coupon_meta.post_id =$id
	)");
		$pcat->execute(array());
	
		$cats = $pcat->fetchAll(PDO::FETCH_ASSOC);
		foreach ($cats as $cat)
			echo "<input type='checkbox' name='checklist[]' value='".$cat['cat_id']."'>".$cat['name']."<br>";	
		?>
		
		</td>
		</table>
		<button type="submit" value="submit">Submit</button>
		</form>
		<?
		
	}
	?>
    
    
    </div><!--#main_admin-->
    
    
    
    <? } //end main if, page to be shown only to admins.
else 
	echo "You are Not Logged in";	
?>
    </div><!--#main-->