<?php 
include('header.php');

if(!isset($_GET['category']))
{
$database = $connection->prepare("SELECT * FROM coupon WHERE status = 1 ORDER BY id DESC");
$database->execute(array());
$result = $database->fetchAll(PDO::FETCH_ASSOC);
$total_elements = count($result);
	if (($total_elements%10) == 0) //Counts the no. of pages needed in the array.
		$total_pages = $total_elements/10;
	else
		$total_pages = ($total_elements/10) + 1;
}
if(isset($_GET['category'])) //If the Category is set.
{
	$cat = $_GET['category'];
	//The part of code to select all posts under a category
$database = $connection->prepare("SELECT * FROM coupon_meta WHERE cat_id=".$cat." ORDER BY post_id DESC"); //need to change $cat
if (!$database)
	echo $connection->errorInfo();
$database->execute(array()); //All the posts are returned
$result = $database->fetchAll(PDO::FETCH_ASSOC);
$total_elements = count($result);
	if (($total_elements%10) == 0) //Counts the no. of pages needed in the array.
		$total_pages = $total_elements/10;
	else
	
		$total_pages = ($total_elements/10) + 1;
}
		
// end here

if(isset($_GET['page_no']))
	$offset = ($_GET['page_no']-1) * 10;
else
	$offset = 0; //array index starts from zero

if(isset($_GET['category']))
{
	$cat = $_GET['category'];
	$database = $connection->prepare("Select * FROM coupon, coupon_meta WHERE status = 1 AND cat_id = '$cat' AND coupon_meta.post_id = coupon.id ORDER BY id DESC LIMIT $offset , 10 "); //Select all the coupons in the table.
	$database->execute(array()); //Get all Questions in an Array
	$result = $database->fetchAll(PDO::FETCH_ASSOC);	
}
else {
	//Display Homepage
$database = $connection->prepare("Select * FROM coupon WHERE status = 1 ORDER BY id DESC LIMIT $offset , 10 "); //Select all the coupons in the table.
$database->execute(array()); //Get all Coupons in an Array
$result = $database->fetchAll(PDO::FETCH_ASSOC);	
}

?>

<div id="codes">

<h3>Latest Coupon Codes</h3>

<?php
//the main loop which displays all the content
foreach ($result as $r) {
		$postid = $r['id'];
		$database = $connection->prepare("Select coupon_meta.cat_id, cats.name FROM coupon_meta, cats WHERE post_id = ".$postid." AND coupon_meta.cat_id = cats.cat_id"); //Select all the coupons in the table + categories.
$database->execute(array()); //Get all Coupons in an Array
$ret_cat= $database->fetchAll(PDO::FETCH_ASSOC);
		
		$edate = strtotime($r['expirydate']);
		$ldate = strtotime($r['lastused']);
		
		echo "<div class='result'>";
		if (strlen($r['coupon'])>17)
			echo "<div id='".$postid."' class='code' style='font-size: 24px !important;' ><p class='copy' id='copy_".$r['id']."'>".$r['coupon']."</p><a href='#' class='copycode'  id='copycode_".$r['id']."'>Copy</a></div>";
		else
			echo "<div id='".$postid."' class='code'><p class='copy' id='copy_".$r['id']."'>".$r['coupon']."</p><a href='#' class='copycode'  id='copycode_".$r['id']."'>Copy</a></div>";
			echo "<div class='expirydate'><strong>Expires on:</strong>".date('d F Y',$edate)."</div>";
			echo "<div class='submittedbye'><strong>Submitted By:</strong> <a href='".$r['url']."'>".$r['submittedby']."</a></div>";
			echo "<div class='lastdate'><strong>Last used on: </strong>".date('d F Y', $ldate)."</div>";
			echo "<div class='description'>".$r['description']."</div>";
			echo "<div class='category'>Category: ";
			
			foreach($ret_cat as $rc)
			{
				$catid = $rc['cat_id'];
				echo "<a href='index.php?category=".$rc['cat_id']."'>".$rc['name']."</a> ";
			}
			echo "</div>";
			echo "<div class='ratings'  id='".$r['id']."'><div class='up'><img src='images/up.png'></div><div class='value up_".$r['id']."'>".$r['success']."</div><div class='down'><img src='images/down.png'></div><div class='valued down_".$r['id']."'>".$r['fail']."</div></div>";
			
			echo "</div>";
	
	?>		<script type="text/javascript">
			$(document).ready(function(){
					$('a#copycode_<?php echo $r['id'] ?>').zclip({
						path:'js/ZeroClipboard.swf',
						copy:$('p#copy_<?php echo $r['id'] ?>').text()
					});
					$('a#copycode_<?php echo $r['id'] ?>').click( function() {
						$('a.copycode').html("Copy");
						$('a#copycode_<?php echo $r['id'] ?>').html("Copied");
					});
		
				});
			</script>	
	<?php
} //endforeach

?>
<br><br><br><center>
<div class="navigation">


<?php 
//Page Numbered navigation
if($total_pages < 12)
{
	for ($i = 1 ; $i <= $total_pages ; $i++)
	{
		if (isset($_GET['category'])) {
		$cat = $_GET['category'];
		echo "<a class='pageno' href=?category=".$cat."&page_no=".$i.">".$i."</a> ";
		}
		else
			echo "<a class='pageno' href=?page_no=".$i.">".$i."</a> ";
	}
}
else {
	//When there are way too many pages, show only some of them.
	$sp = (isset($_GET['page_no'])) ? $_GET['page_no'] : 1 ;
	$ep = (isset($_GET['page_no'])) ? $_GET['page_no'] + 5 : 5 ;
	
	for ($i = $sp ; (($i <= $ep) && ($i < $total_pages)) ; $i++)
	{
		if (isset($_GET['category'])) {
		$cat = $_GET['category'];
		echo "<a class='pageno' href=?category=".$cat."&page_no=".$i.">".$i."</a> ";
		}
		else
			echo "<a class='pageno' href=?page_no=".$i.">".$i."</a> ";
	}
	echo "<span class='pagebreaker'>.......</span>";
	$next_page = (isset($_GET['page_no'])) ? $_GET['page_no'] + 1 : 2; 
	$last_page = $total_pages;
	
	if (isset($_GET['category'])) {
		$cat = $_GET['category'];
		echo "<a class='pageno' href=?category=".$cat."&page_no=".$next_page.">Next</a> ";
		}
	else
		echo "<a class='pageno' href=?page_no=".$i.">Last</a> ";
	
}
	
	?>
    
    
</div>
</center>
</div>
<?php if (isset($_GET['page_no'])) { //To Change the Color of Current Page Tab
	?>
    <script type="text/javascript">
	$(document).ready( function() { 
		$(".pageno:contains('<?php echo $_GET['page_no']; ?>')").css("background","#BBB");		
	});
	</script>
<?php 
}
if(isset($_COOKIE['admin_coupon']))
{
	?>
    <script>
	$(document).ready(function() {
		$('.code').dblclick( function() {
			var delid = $(this).attr('id');
			var query = 'id='+delid;
			$.get('admin/unapprove.php', query, function() {});
			$(this).parent().fadeOut(1300);
		
		}); //end dblclick
	});
	</script>
    <?
}
include('sidebar.php');
include('footer.php'); 
?>