
<footer>
<? include('footertext.php'); ?>
</footer>

</div><!--#main-->
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
</body>
</html>
