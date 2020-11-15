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
        <p class="text-justify">Welcome, <?php echo $email; ?></br></p>
        <div class="row col-sm -12">
            <div class="card col-sm-4">
                <div class="card-body">
                    <h4 class="card-title">Open Account</h4>
                    <p class="card-text">Come take a look at the many banking options you have.</p>
                    <a href="test_create_accounts.php" role="button" class="btn btn-dark">Set Up Account</a>
                </div>
            </div>

            <div class="card col-sm-4">
                <div class="card-body">
                    <h4 class="card-title">View Accounts</h4>
                    <p class="card-text">Come take a look at the many banking options you have.</p>
                    <a href="test_create_accounts.php" role="button" class="btn btn-dark">View Your Accounts</a>
                </div>
            </div>
        </div>

    </div>











    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>