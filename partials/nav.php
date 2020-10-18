<?php
require_once(__DIR__ . "/../lib/helpers.php");
?>

<style>
<?php include 'theme.css'; ?>
</style>


<div class="navbar">
    <a class="navItem" href="home.php">Home</a>
    <?php if (!is_logged_in()): ?>
        <a class="navItem" href="login.php">Login</a>
        <a class="navItem" href="register.php">Register</a>
    <?php endif; ?>
    <?php if (is_logged_in()): ?>
        <a class="navItem" href="profile.php">Profile</a>
        <a class="navItem" href="logout.php">aLogout</a>
    <?php endif; ?>
</div>


