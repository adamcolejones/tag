<?php
	require "header.php";
?>
	<div class="homeBody">

		<p>Starting Settings Filler</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>This button updates the users profile picture.</p>
			<?php 
				if (isset($_SESSION['userID'])) {
					echo "	

						<div class='updateprofilepic'>
							<form action='profilepic.php' method='POST' enctype='multipart/form-data'>
								<input type='file' name='file'>
								<button type='submit' name='submit'>Profile Image</button>
							</form>
						</div>


						";
				}
				else {
					echo "<br><br> You must be logged in to post comments! <br><br>";
				}

			?>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>-</p>
		<p>Ending Filler</p>
	</div>

<?php
	require "footer.php";
?>