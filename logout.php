<?php
/*******w******** 
    
    Name: Aidan Bridges 
    Date: 2023-12-04
    Description: log out page for final project

****************/
require('connect.php');
// Start or resume session
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page or any other page after logout
header("Location: index.php");
exit();
?>
