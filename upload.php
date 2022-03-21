<?php
	require "header.php";
?>
<div class="uploadBody">
<!-- Ask the user to select a link based on what they want to post, then set the url to showcase that choice and use url get to select the correct forms -->

<!-- I'm pretty Sure that I don't need this file anymore, check the new file post.php to be sure. -->
	<?php 
				if (isset($_SESSION['userID'])) {
					echo "<form enctype='multipart/form-data' method='POST' action='".setUploads($conn)."'>	

						<input type='hidden' name='userid' value='".$_SESSION['userID']."'>
						<input type='hidden' name='date' value='".date('Y-m-d H:i:s')."'>

						<label for='title'>Title: </label>
						<textarea name='title'></textarea><br>

						<label for='description'>Description: </label>
						<textarea name='description'></textarea><br><br>

						<input type='file' name='file'>
						
									
						<button type='submit' name='uploadSubmit'>Upload</button>
									
						</form>";
				}
				else {
					echo "<br><br> You must be logged in to upload posts! <br><br>";
				}
	?>

</div>
<?php
	require "footer.php";
?>