<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>

<?php
$query = "";

if (isset($_POST["query"])) {
    $query = $_POST["query"];
}
if (isset($_POST["search"]) && !empty($query)) {
    $db = getDB();
    /*
    $stmt = $db->prepare("SELECT id, account_number, user_id, account_type, opened_date, balance, apy, active, frozen
        from Accounts WHERE account_number=:q LIMIT 1");
    
    $r = $stmt->execute([":q" => "%$query%"]);

    if ($r) {
        $result = $stmt->fetch();
    }
    else {
        flash("There was a problem fetching the results");
    }
    */
    $sql = $db->prepare("UPDATE Accounts SET frozen=1 WHERE account_number=:q");
    $res = $sql->execute([":q" => "%$query%"]);


}
?>

<h1>Freeze Account</h1>
<form method="POST">
    <input name="query" placeholder="Search" value="<?php safer_echo($query); ?>"/>
    <input type="submit" value="Search" name="search"/>
</form>