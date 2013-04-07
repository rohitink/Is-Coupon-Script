<?php
include('../functions.php');  ?>

<div id="codes">
<h3>Admin Panel</h3>
<?php
	if(isset($_POST['username']))
	{
			$username = $_POST['username'];
			if ($username != "admin") {
				echo "Please enter username as 'admin', if you are trying to create an admin account.<br>";
				echo "<small>With the current version of script, only admin accounts are possible</small>";
			}
			else {
					$stmt = $connection->prepare("SELECT * FROM admin WHERE username = $username");
					$stmt->execute(array());
					$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
					
					if (count($result) >= 1) {
						echo "Admin Account Already Exists";
					}
					else {
					//Each User has its unique HASH
					$safepass = generateSecureHash($_REQUEST['pass'], $_REQUEST['username']); //Hash the Entered Pass, and store it in variable.
						
					$stmt = $connection->prepare('INSERT INTO admin (username, password, email) VALUES (:username, :safepass, :email)');
					$stmt->execute(array(
					"username" => $_REQUEST['username'],
					"safepass" => $safepass,
					"email" => $_REQUEST['email'])
					);
					
					echo "<br>You are Now the Administrator of this Site. Please <a href='admin.php'>Log in</a> to Continue.<br>";	
					}
			}
	}
	else
	{ ?>
			<form method='post' action='create.php'>
            <input type="text" name='username' placeholder="username" required autocomplete='off' autofocus>
            <input type="password" name='pass' placeholder="password" required>
            <input type="email" name="email" placeholder="email" required>
            <input type="submit" value="Sign Up">
            </form>
<?php }
echo "</div>";
include('../footer.php');
?>