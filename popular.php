<?php
include('header.php');
include('dbconnect.php');

$database = $connection->prepare("SELECT * FROM coupon WHERE status = 1 ORDER BY success DESC LIMIT 0,15");
$database->execute(array());
$result = $database->fetchAll(PDO::FETCH_ASSOC);

?>
<div id="codes">

<h3>Our Top 15 Coupon Codes</h3>

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
			echo "<div class='code' style='font-size: 24px !important;' ><p class='copy' id='copy_".$r['id']."'>".$r['coupon']."</p><a href='#' class='copycode'  id='copycode_".$r['id']."'>Copy</a></div>";
		else
			echo "<div class='code'><p class='copy' id='copy_".$r['id']."'>".$r['coupon']."</p><a href='#' class='copycode'  id='copycode_".$r['id']."'>Copy</a></div>";
			echo "<div class='expirydate'><strong>Expires on:</strong>".date('d F Y',$edate)."</div>";
			echo "<div class='submittedbye'><strong>Submitted By:</strong> <a href='".$r['url']."'>".$r['submittedby']."</a></div>";
			echo "<div class='lastdate'><strong>Last used on: </strong>".date('d F Y', $ldate)."</div>";
			echo "<div class='description'>".$r['description']."</div>";
			echo "<div class='category'>Category: ";
			
			foreach($ret_cat as $rc)
			{
				$catid = $rc['cat_id'];
				//$database = $connection->prepare("Select * FROM cats WHERE cat_id= ".$catid); //Select all the coupons in the table.
//$database->execute(array()); //Get all Questions in an Array
//$ret_cat_name= $database->fetch(PDO::FETCH_ASSOC);
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
<br><br><br>
</div>
<?php 
include('sidebar.php');
include('footer.php'); ?>

 	<script type="text/javascript">
		function getCookie(name) { //function to check if cookie exists
				var dc = document.cookie;
				var prefix = name + "=";
				var begin = dc.indexOf("; " + prefix);
				if (begin == -1) {
					begin = dc.indexOf(prefix);
					if (begin != 0) return null;
				}
				else
				{
					begin += 2;
					var end = document.cookie.indexOf(";", begin);
					if (end == -1) {
					end = dc.length;
					}
				}
				return unescape(dc.substring(begin + prefix.length, end));
			} 
			
			
		$('.up').click( function() {
				
				var ID = $(this).parent().attr('id');
				if (getCookie("upvote"+ID) != null) 
					alert("You Have already voted this UP");
				else {	
					var value = $(".up_"+ID).html();
					var otherValue = $(".down_"+ID).html();
					value = Number(value) + 1;
					$(this).fadeOut(100).fadeIn(100);
					if (getCookie("downvote"+ID) != null) { $(".down_"+ID).html(Number(otherValue)-1); }
					var querystring = 'id='+ID;
					$.get('upvote.php', querystring, function(data) { $(".up_"+ID).html(value); });
					var upvoted = "TRUE"+ID;
					return false;
					}	
			
		});
		
		$('.down').click( function() {
				var ID = $(this).parent().attr('id');
				if (getCookie("downvote"+ID) != null)
					alert("You Have Already Downvoted this!");
				else {	
					var value = $(".down_"+ID).html();
					var otherValue = $(".up_"+ID).html();
					value = Number(value) + 1;
					$(this).fadeOut(100).fadeIn(100);
					if (getCookie("upvote"+ID) != null) { $(".up_"+ID).html(Number(otherValue)-1); }
					var querystring = 'id='+ID;
					$.get('downvote.php', querystring, function(data) { $(".down_"+ID).html(value); });
					return false;
					}
		});
		
		
		
		
	</script>

