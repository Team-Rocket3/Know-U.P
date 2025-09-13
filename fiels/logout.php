<?php
session_start();
if (!isset($_GET['logout']) || $_GET['logout'] === 'true') {
    session_unset();
    session_destroy();
    header("Location: ../index.html"); 
    exit();
}
?>



