<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>


<?php // FREEZE ACCOUNT
$query = "";

if (isset($_POST["query"])) {
    $query = $_POST["query"];
}
if (isset($_POST["search"]) && !empty($query)) {
    $db = getDB();
    $sql = $db->prepare("UPDATE Accounts SET frozen=1 WHERE account_number=:q");
    $res = $sql->execute([":q" => $query]);
    if($res){
        flash("Updated");
    }
}
?>

<h1>Freeze Account</h1>
<form method="POST">
    <input name="query" placeholder="Disable" value="<?php safer_echo($query); ?>"/>
    <input type="submit" value="Freeze" name="search"/>
</form>


<?php // FREEZE ACCOUNT
$query2 = "";

if (isset($_POST["query2"])) {
    $query2 = $_POST["query2"];
}
if (isset($_POST["search"]) && !empty($query2)) {
    $db = getDB();
    $sql = $db->prepare("UPDATE BankUsers SET disabled=1 WHERE username=:q");
    $res = $sql->execute([":q" => $query2]);
    if($res){
        flash("Updated");
    }
    echo($query);
}
?>

<h1>Disable User</h1>
<form method="POST">
    <input name="query2" placeholder="Disable" value="<?php safer_echo($query2); ?>"/>
    <input type="submit" value="Disable" name="search"/>
</form>