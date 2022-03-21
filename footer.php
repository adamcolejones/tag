<footer>
	<div id='footerContainer'>
		<ul>
		<?php 
			if (isset($_SESSION['userID'])) {
				echo "<li class='footerList button'><a href='home.php?user=".$_SESSION['userUserName']."'>Home</a></li>";
			} else {
				echo "<li class='footerList button'><a href='signup.php'>Home</a></li>";
			}
		?>
				<li class='footerList button'><a href='index.php'>Activity</a></li>
				<li class='footerList button'><a href='index.php'>Trending</a></li>
			</ul>
		</div>
	</footer>
</body>
</html>
	

