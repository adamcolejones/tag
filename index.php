<?php
	require "header.php";
?>

<!-- There is supposed to be a body tag here instead of the div tag "homeBody" -->

	<div class="homeBody">
<!-- This is code for displaying videos with html, keep until you copy it elsewhere

		<video width="320" height="240" controls>
			<source src="videos/sample.mp4" type="video/mp4">
		Your browser does not support the video tag.
		</video>
-->

<!--This php code assaigns comment values and calls the function that makes comments-->

<!-- This is code for a comment box that is no longer required on this page.  You can delete this section when you copy it over to the post page.

					<?php 
					/*	if (isset($_SESSION['userID'])) {
							echo "<form method='POST' action='".setComments($conn)."'>
								<input type='hidden' name='userid' value='".$_SESSION['userID']."'>
								<input type='hidden' name='date' value='".date('Y-m-d H:i:s')."'>
								<textarea name='comment'></textarea><br>
								<button type='submit' name='commentSubmit'>Comment</button>
							</form>";
						}
						else {
							echo "<br><br> You must be logged in to post comments! <br><br>";
						}*/
					?>
-->

		<?php
			getActivityPosts($conn);
		?>
		
	</div>
<?php
	require "footer.php";
?>



<!-- Use this page to reroute to activity page -->