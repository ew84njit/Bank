<!DOCTYPE html>
<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//we use this to safely get the email to display
$email = "";
if (isset($_SESSION["user"]) && isset($_SESSION["user"]["email"])) {
    $email = $_SESSION["user"]["email"];
}
?>

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
    <div class="container pt-3 my-3">
        <?php if (is_logged_in()): ?>
            <p class="text-justify ">Welcome, <?php echo $email; ?></br></p>
        <?php else: ?>
            <p> Please Login Or Register </p>
        <?php endif; ?>
        <div class="row col-sm-12">
            <div class="card col-sm-4 md-3">
                <div class="card-body">
                    <h4 class="card-title">Open</h4>
                    <p class="card-text">Open up a new account.</p>
                    <a href="test_create_accounts.php" role="button" class="btn btn-dark">Set Up Account</a>
                </div>
            </div>

            <div class="card col-sm-4 md-3">
                <div class="card-body">
                    <h4 class="card-title">View</h4>
                    <p class="card-text">Take a look at your accounts. </p>
                    <a href="test_view_accounts.php" role="button" class="btn btn-dark">View Your Accounts</a>
                </div>
            </div>
            <div class="card col-sm-4 md-3">
                <div class="card-body">
                    <h4 class="card-title">Deposit</h4>
                    <p class="card-text">Put money into your account.</p>
                    <a href="test_create_transactions.php" role="button" class="btn btn-dark">Deposit</a>
                </div>
            </div>
            <div class="card col-sm-4 md-3">
                <div class="card-body">
                    <h4 class="card-title">Withdraw</h4>
                    <p class="card-text">Withdraw funds from your account.</p>
                    <a href="test_create_transactions.php" role="button" class="btn btn-dark">Withdraw</a>
                </div>
            </div>
            <div class="card col-sm-4 md-3">
                <div class="card-body">
                    <h4 class="card-title">Transfer</h4>
                    <p class="card-text">Move funds between accounts.</p>
                    <a href="transfer.php" role="button" class="btn btn-dark">Transfer</a>
                </div>
            </div>
            <div class="card col-sm-4 md-3">
                <div class="card-body">
                    <h4 class="card-title">Profile</h4>
                    <p class="card-text">View your profiile.</p>
                    <a href="profile.php" role="button" class="btn btn-dark">View Profile</a>
                </div>
            </div>
        </div>

    </div>











    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>