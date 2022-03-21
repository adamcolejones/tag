<?php
session_start();
include_once 'includes/dbh.inc.php';
$id = $_SESSION['userID'];

if (isset($_POST['submit'])) {
	$file = $_FILES['file'];

	$fileName = $file['name'];
	$fileTmpName = $file['tmp_name'];
	$fileSize = $file['size'];
	$fileError = $file['error'];
	$fileType = $file['type'];

	$fileExt = explode('.', $fileName);
	$fileActualExt = strtolower(end($fileExt));

	$allowed = array('jpg', 'jpeg', 'png');


	if (in_array($fileActualExt, $allowed)) {
		if ($fileError === 0) {
			if ($fileSize < 1000000) {

//this code deletes the old profile img

				$filename = "profilepics/profile".$id."*";
				$fileinfo = glob($filename);
				$fileExt2 = explode(".", $fileinfo[0]);
				$fileActualExt2 = $fileExt2[1];
				$newfile = "profilepics/profile".$id.".".$fileActualExt2;
				if (!unlink($newfile)) {
					echo "File was not deleted!";
				} else {
					echo "File was deleted!";
				}

// this code continues to place the new file into the correct spot

				$fileName = "profile".$id.".".$fileActualExt;
				$fileDestination = 'profilepics/'.$fileName;
				move_uploaded_file($fileTmpName, $fileDestination);
				$sql = "UPDATE users SET profileimg=1 WHERE userid='$id'";
				$result = mysqli_query($conn, $sql);
				header("Location: settings.php?uploadsuccess");
			} else {
				echo "Your file is too big!";
			}
		} else {
			echo "There was an error uploading your file!";
		}
	} else {
		echo "You cannot upload files of this type!";
	}
}