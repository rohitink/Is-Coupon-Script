<? 
//ADMIN INDEX FILE
include('../functions.php');
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
    <a href='index.php?tab=pages'>Pages</a>
    <a class='logout' href="logout.php">Log Out</a><br><br>
    </div>
    <div id='main_admin'>		
<?
	if (!isset($_GET['tab']))
	{
		$_GET['tab'] = "none";	
	}
	switch($_GET['tab'])
	{
		case "pending":
			//This part shall Display all the Pending Coupons
			$pending = $connection->prepare("SELECT * FROM coupon WHERE status = 0 LIMIT 0, 15");
			$pending->execute(array());
			$result = $pending->fetchAll(PDO::FETCH_ASSOC);
			foreach($result as $r)
			{ 
			$postid = $r['id'];
			?>
            	<br />
				<div id='<?=$r['id']?>' class='codes'>
				<strong>Coupon Id:</strong> <?=$r['id']?><br>
                <strong>Code:</strong> <span class='code'><?=$r['coupon']?></span><br>
                <strong>Description:</strong> <?=$r['description']?><br>
                <strong>Categories: </strong>
                <? 
				$database = $connection->prepare("Select coupon_meta.cat_id, cats.name FROM coupon_meta, cats WHERE post_id = ".$postid." AND coupon_meta.cat_id = cats.cat_id"); //Select all the questions in the table + categories.
				if(!$database)
					echo $connection->errorInfo();
				$database->execute(array()); //Get all Coupons in an Array
				$ret_cat= $database->fetchAll(PDO::FETCH_ASSOC);
				
				foreach($ret_cat as $rc)
					echo $rc['name']." ";
				//Need to do something to show categories here as well ?>
				<br><span class='approve'>Approve</span> | 
                <span class='delete'>Delete</span>
                <span class='edit'>Edit</span>
                </div>
                
		<?	}
			break;
		case "navigation":
			echo "Choose What All Categories to Show in Menubar:<br> ";
			$database = $connection->prepare("SELECT * FROM cats");
				$database->execute(array());
				$fetched_cats = $database->fetchAll(PDO::FETCH_ASSOC);
				foreach($fetched_cats as $fc)
				{
					if($fc['status']==1)
						echo "<input class='cat' type=checkbox id='".$fc['cat_id']."' name='".$fc['cat_id']."' checked><a href='../index.php?category=".$fc['cat_id']."' title='".$fc['name']."'>".$fc['name']."</a><br>";
					else
						echo "<input class='cat' type=checkbox id='".$fc['cat_id']."' name=".$fc['cat_id']."><a href='../index.php?category=".$fc['cat_id']."' title='".$fc['name']."'>".$fc['name']."</a><br>";
								
				} 
				?>
                <script>
				$('input[type=checkbox]').change(function() {
					var id= $(this).attr('id');
					if($(this).is(':checked')) {
						//Set Status 1
						$.get('acat.php','id='+id, function() {});
					}
					else {
						//Set Status 0
						$.get('hcat.php','id='+id, function() {});
					}
				});
				</script>
                <?
            
			break;
		case "categories":
			?>
			Add New Category:<br>
			<form id='addcat' method='get' name="addnewcat" action='index.php?tab=categories'>
			<input type="text" name="newcat"><br>
			<input type="submit" name='addsubmit' value="Add">
			</form>
			
			Delete A Category:<br>
			<form id='deletecat' name="deletecat">
			<select name='dcat'>
			<?
			//Display all categories in the dropdown menu
			$cats = $connection->prepare("SELECT * FROM cats");
			$cats->execute(array());
			$result = $cats->fetchAll(PDO::FETCH_ASSOC);
			foreach($result as $r)
				echo "<option value='".$r['name']."'>".$r['name']."</option>";
			//REMEMBER TO UPDATE ALL THE TABLES AND NOT ONLY THE CATEGORY TABLE	
			?>
            </select>
			<input type="submit" value="Delete!">
			</form>
            <script>
			//Code to Delete and Update Categories
			$('#addcat').submit( function() {
				var data = $(this).serialize();
				$.post('addcat.php',data, function(data) {
					$(location).attr('href','index.php?tab=categories');
					alert("Category Was Successfully Added");
					});
				return false;	
			});
			
			$('#deletecat').submit( function() {
				var data = $(this).serialize();
				$.post('deletecat.php',data, function(data) {
					$(location).attr('href','index.php?tab=categories');
					alert("Category Was Successfully Deleted");
					});
				return false;	
			});
			
			</script>
			
			<?
				break;
		case "options":
				if (isset($_POST['optsub']))
				{
				$database = $connection->prepare("UPDATE options SET title = '".$_POST['title']."', url = '".$_POST['url']."',homedesc='".$_POST['tagline']."', metatitle = '".$_POST['mtitle']."' , metakeywords = '".$_POST['mkey']."' WHERE id=1"); 
				$database->execute(array());
				echo "Your Settings were Saved.<br><br>";
				}
				$database = $connection->prepare("SELECT * FROM options WHERE id=1");
				$database->execute(array());
				$r = $database->fetch(PDO::FETCH_ASSOC);
				?>
				<form name='options' id='options' method="post">
				<label for='title'>Site Title</label>
				<input type="text" name="title" value="<? echo htmlspecialchars($r['title']); ?>" size=60><br>
				<label for='url'>Site Url</label>
				<input type="text" name="url" value='<?=$r['url']?>' size=60><br>
				<label for='tagline'>Tagline</label>
				<input type="text" name="tagline" value='<?=$r['homedesc']?>' size=60><br>
				<label for='mtitle'>Meta Title</label>
				<input type="text" name="mtitle" value='<?=$r['metatitle']?>' size=60><br>
				<label for='mkey'>Meta Keywords</label>
				<input type="text" name="mkey" value='<?=$r['metakeywords']?>' size=60><br>
				<input  name='optsub' type="submit" id='submit' value='Update'>
				</form>
                <h5>Change Password</h5>
                <form method="post">
                New Password: <input type="password" name='newpass'><br />
                Re-Enter: <input required type='password' name='newpassagain'><br />
                <input required type="submit" value="Change" name="changepass">
                </form>
                <?
                if(isset($_POST['changepass']))
				{
					if($_POST['newpass'] == $_POST['newpassagain'])
					{
						$pass = generateSecureHash($_POST['newpass'], 12324234);
						$updatepass = $connection->prepare("UPDATE admin SET password = '".$pass."' WHERE username='admin'");
						$updatepass->execute(array());
						echo "Password Updated.";
					}
					else
						echo "Entered Passwords Do not Match.";
				}
				break;
		case 'pages':
			if(isset($_POST['updsid']))
			{
				file_put_contents('../sidebar.php', $_POST['sidefile']);
				file_put_contents('../contactdetails.php', $_POST['contfile']);
				file_put_contents('../footertext.php', $_POST['footfile']);
				echo "Changes Were Saved.";
			}
			$str = file_get_contents('../sidebar.php');
			$contact = file_get_contents('../contactdetails.php');
			$foot = file_get_contents('../footertext.php');
			?>
            <h4> Sidebar</h4>
            <form name="sidebarupdate" method="post">
			<textarea rows=17 cols=100 name='sidefile'><?=$str?>
            </textarea><br>
            <h4>Contact Us Page</h4>
            <textarea rows=5 cols=100 name='contfile'><?=$contact?>
            </textarea><br>
            <h4>Footer Content</h4>
            <textarea rows=3 cols=100 name='footfile'><?=$foot?>
            </textarea><br>
            <input type='submit' name='updsid' value="Save Changes">
            </form>
            <?
			break;
		default:
			echo "Please Select an Option above to start.";
	}
	?> </div><!--#main_admin-->
    <script>
	$(document).ready( function() {
		$('.approve').click( function() {
			var id = $(this).parent().attr('id');
			var query = "id="+id;
			$.get('approve.php',query,function() {
				$('#'+id).fadeOut();
			});
		
		});//end approve click
		$('.delete').click( function() {
			var id = $(this).parent().attr('id');
			var query = "id="+id;
			$.get('delete.php',query,function() {
				$('#'+id).fadeOut();
			});
		});//end delete click
		$('.edit').click( function() {
				var id = $(this).parent().attr('id');
				var query = 'id='+id;
				$(location).attr('href','edit.php?'+query);
		});//end edit click
	});//end ready
    </script>
    <?
}
else  //If the User is not Logged In
{
	log_in_form();
?>
<script>
$(document).ready( function() {
	$('#adminlogin').submit( function() {
		var query = $(this).serialize();
		$.post('logsx.php',query,function(data) {
			if(data == "success")
			{
				document.write("Log In Successful");
				var href='index.php';
				$(location).attr('href',href);
			}
			else
				alert("Log in Failed. Please Try Again");
		}); //end Ajax call
		return false;
	});
	
}); //end ready
</script>
<?
} //end else if user not logged in
if(isset($_POST['forgotsubmit']))
{
	$mail = $_POST['mail'];

	$stmt = $connection->prepare("SELECT * FROM admin WHERE email = :mail");
	$stmt->execute(array(':mail' => $mail));
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	
	if ($result['username'] == 'admin')
	{
	$rand = mt_rand(1234,9999);
	
	$safePass = generateSecureHash($rand, 12324234);
	$stmt = $connection->prepare("UPDATE admin SET password = :password WHERE email = :mail");
	$stmt->execute(array(':password' => $safePass, ':mail' => $mail));
	 
	
	$to      = $mail;
	$subject = 'Password Reset For Your Coupon Site';
	$message = 'Hello, Your New Password is '.$rand;
	$headers = 'From: ' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	
	mail($to, $subject, $message, $headers);
	echo "Your Password was Reset. Check Your Junk Folder.";
}
else
{
	echo "Invalid Email";
}
	
}
?>
</div>
</body>
</html>
