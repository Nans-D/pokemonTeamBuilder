<?php

include '../config.php';

session_start();

if (isset($_SESSION['name']) || $_SESSION['email']) {
    unset($_SESSION['name']);
    unset($_SESSION['email']);
}

header('Location: ../index.php');
exit;
