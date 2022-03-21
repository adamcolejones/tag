<?php
	require "header.php";
?>
<div class="uploadBody">
<!-- Ask the user to select a link based on what they want to post, then set the url to showcase that choice and use url get to select the correct forms -->
	<?php 
				if (isset($_SESSION['userID'])) {

					$type = $_GET["type"];

					if ($type == "none") {
						echo 'What kind of post would you like to make?';
						echo '<div class="TextButton"><a href="post.php?type=text">Text</a></div>';
						echo '<div class="PictureButton"><a href="post.php?type=picture">Picture</a></div>';
					}
					else {
						if ($type == "text") {
							echo "<form enctype='multipart/form-data' method='POST' action='".postText($conn)."'>	
									<input type='hidden' name='userid' value='".$_SESSION['userID']."'>
									<input type='hidden' name='date' value='".date('Y-m-d H:i:s')."'>
									<label for='title'>Title: </label>
									<textarea name='title'></textarea><br>
									<label for='body'>Body: </label>
									<textarea name='body'></textarea><br><br>			
									<button type='submit' name='textSubmit'>Post</button>			
									</form>";
						}
						else if ($type == "picture") {

							echo "<form enctype='multipart/form-data' method='POST' action='".postPicture($conn)."'>	
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
						else if ($type == "video") {

						}
					}
				}
				else {
					echo "<br><br> You must be logged in to upload posts! <br><br>";
				}
	?>

</div>
<?php
	require "footer.php";
?>