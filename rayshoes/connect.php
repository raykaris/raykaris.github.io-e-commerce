<?php

// Database connection for login
$loginDbHost = "localhost";
$loginDbUser = "root"; 
$loginDbPass = ""; 
$loginDbName = "login";

$loginConn = new mysqli($loginDbHost, $loginDbUser, $loginDbPass, $loginDbName);
if ($loginConn->connect_error) {
    die("Connection failed: " . $loginConn->connect_error);
}

// Database connection for cart system
$cartDbHost = "localhost";
$cartDbUser = "root";
$cartDbPass = ""; 
$cartDbName = "cart-system"; 

$cartConn = new mysqli($cartDbHost, $cartDbUser, $cartDbPass, $cartDbName);
if ($cartConn->connect_error) {
    die("Connection failed: " . $cartConn->connect_error);
}

?>