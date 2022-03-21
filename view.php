<?php
	require "header.php";
?>
<div class="homeBody">
		<?php
			getPost($conn);
		?>
</div>

<?php
	require "footer.php";
?>