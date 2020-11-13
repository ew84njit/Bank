<!DOCTYPE html>
<?php
require_once(__DIR__ . "/../lib/helpers.php");
?>

<style>
<?php include 'theme.css'; ?>
</style>

<body>
<div class="navbar">
    <a class="navItem" href="home.php">Home</a>
    <?php if (!is_logged_in()): ?>
        <a class="navItem" href="login.php">Login</a>
        <a class="navItem" href="register.php">Register</a>
    <?php endif; ?>
    <?php if (has_role("Admin")): ?>
        <a class="navItem" href="test_create_accounts.php">Create Accounts</a>
        <a class="navItem" href="test_list_accounts.php">View Accounts</a>
        <a class="navItem" href="test_create_transactions.php">Make Transactions</a>
        <a class="navItem" href="test_list_transactions.php">View Transactions</a>
    <?php endif; ?>
    <?php if (is_logged_in()): ?>
        <a class="navItem" href="profile.php">Profile</a>
        <a class="navItem" href="logout.php">Logout</a>
    <?php endif; ?>
</div>
</body>

