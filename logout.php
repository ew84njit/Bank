<?php
session_start();
session_unset();
session_destroy();
?>
<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
echo "You're logged out now";
header("Location: login.php");
?>