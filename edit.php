<? 
//ADMIN EDIT FILE
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
    <a class='logout' href="logout.php">Log Out</a><br><br>
    </div>
    <div id='main_admin'>	
    
    </div><!--#main_admin-->
    
    
    
    <? } //end main if, page to be shown only to admins.
else 
	echo "You are Not Logged in";	
?>
    </div><!--#main-->