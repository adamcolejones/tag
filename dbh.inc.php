<?php

$servername = "localhost";
$dBUsername = "root";
$dBPassword = "3321fcbg6hk4";
$dBName = "tagloginsystem";

$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);

if (!$conn) {
	die("Connection failed: ".mysqli_connect_error());
}
