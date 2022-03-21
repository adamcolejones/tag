<?php
	require "header.php";
	if (isset($_SESSION['userID'])) {
		header("Location: index.php");
	}
?>
		<div class="signupBody">
			<h2>Welcome!</h2>
			<?php 
				if (isset($_GET["error"])) {
					if ($_GET["error"] == "emptyfields") {
						echo '<p>Fill in all fields!</p>';
					}
					else if ($_GET["error"] == "invalidemail") { 
						echo '<p>Invalid Email!</p>';
					}
				}
				else if (isset($_GET["signup"])) {
					if ($_GET["signup"] == "success") {
						echo '<p>Signup Sucessful!</p>';
					}
				}
			?>		
			<form action="includes/signup.inc.php" method="post">
				<label for="firstName">First Name: </label>
				<input type="text" name="firstName" placeholder="First Name">
				<br>
				<label for="lastName">Last Name: </label>
				<input type="text" name="lastName" placeholder="Last Name">
				<br>
				<label for="userName">User Name: </label>
				<input type="text" name="userName" placeholder="User Name">
				<br>
				<label for="email">E-mail: </label>
				<input type="text" name="email" placeholder="E-mail">
				<br>
				<label for="password">Password: </label>
				<input type="password" name="password" placeholder="Password">
				<br>
				<label for="pwd-repeat">Repeat Password: </label>
				<input type="password" name="pwd-repeat" placeholder="Repeat password">
				<br>
				<?php
					echo "<input type='hidden' name='date' value='".date('Y-m-d H:i:s')."'>";
				?>
				
				<button type="submit" name="signup-submit">Signup</button>
			</form>
		</div>

<?php
	require "footer.php";
?>
