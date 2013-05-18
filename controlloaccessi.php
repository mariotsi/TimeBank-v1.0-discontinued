<?php
include_once "Bcrypt.php";
session_start();
$username = isset($_POST['username']) ? $_POST['username'] : $_SESSION['username'];
$username = isset($_POST['password']) ? $_POST['password'] : $_SESSION['password'];
if (!isset($username)) {
    echo "non settato";
} else {
    echo "settato";
} ?>
