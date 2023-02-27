<?php

// This function checks if the user has access to a specific role
function access($role)
{
    // If the user is not authorized to access the role, redirect them to a denied page
    if(isset($_SESSION["ACCESS"]) && !$_SESSION["ACCESS"][$role])
    {
        header("Location: ../public/denied.php");
        die;
    }
}

// Set the ACCESS session variable for different roles
$_SESSION["ACCESS"]["ADMIN"] = isset($_SESSION['SESSION_ROLE']) && trim($_SESSION['SESSION_ROLE']) == "admin";
$_SESSION["ACCESS"]["NGO"] = isset($_SESSION['SESSION_ROLE']) && (trim($_SESSION['SESSION_ROLE']) == "ngo" || trim($_SESSION['SESSION_ROLE']) == "admin");
$_SESSION["ACCESS"]["USER"] = isset($_SESSION['SESSION_ROLE']) && (trim($_SESSION['SESSION_ROLE']) == "user" || trim($_SESSION['SESSION_ROLE']) == "admin");

?>
