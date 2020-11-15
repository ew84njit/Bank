<!DOCTYPE html>
<?php
require_once(__DIR__ . "/../lib/helpers.php");
?>

<style>
<?php include 'theme.css'; ?>
</style>
<html>
<head>
    <title>Bank</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>

<body>
    <div class="navbar bg-dark">
        <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
        <?php if (!is_logged_in()): ?>
            <a class="nav-link" href="login.php">Login</a>
            <a class="nav-link" href="register.php">Register</a>
        <?php endif; ?>
        <?php if (has_role("Admin")): ?>
            <a class="nav-link" href="test_create_accounts.php">Create Accounts</a>
            <a class="nav-link" href="test_list_accounts.php">View Accounts</a>
            <a class="nav-link" href="test_create_transactions.php">Make Transactions</a>
            <a class="nav-link" href="test_list_transactions.php">View Transactions</a>
        <?php endif; ?>
        <?php if (is_logged_in()): ?>
            <a class="nav-link" href="profile.php">Profile</a>
            <a class="nav-link" href="logout.php">Logout</a>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>