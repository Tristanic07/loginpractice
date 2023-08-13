<?php
session_start();
require_once 'config.php';


// Clear and destroy the session
session_unset();
session_destroy();
session_regenerate_id(true);



// Redirect to the index page after logout
header('location: login.php');
exit(); // Ensure that no further code is executed after redirection
