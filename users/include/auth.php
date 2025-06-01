<?php
session_start();

$timeout_duration = 900;

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: ../login.php?timeout=true");
    exit();
}

$_SESSION['LAST_ACTIVITY'] = time();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_full_name = $_SESSION['username'];
?>

