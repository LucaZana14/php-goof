<?php
$connection = 'db';
$username = 'phpgoof';
$password = 'phpgoof';
$database = 'phpgoof';

session_start();

$conn = mysqli_connect($connection, $username, $password, $database);

?>