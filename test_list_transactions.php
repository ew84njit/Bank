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
$results = [];

if (isset($_POST["query"])) {
    $query = $_POST["query"];
}

$db = getDB();
$user_id = get_user_id();

$stmtActs = $db->prepare("SELECT id, account_number, user_id, account_type, opened_date, balance 
    from Accounts WHERE id=:user_id");
$rActs = $stmtActs->execute([":user_id"=>$user_id]);
if ($rActs) {$acts = $stmtActs->fetch();}
else {flash("There was a problem fetching the results");}

echo($acts["user_id"]);




$stmt = $db->prepare("SELECT Transactions.id, Transactions.act_src, Transactions.act_dest_id, 
    Transactions.amount, Transactions.action_type, Transactions.created, Accounts.user_id
    from Transactions INNER JOIN Accounts ON Transactions.act_src= LIMIT 10");
$r = $stmt->execute();

if ($r) {
    $results = $stmt->fetch();
}
else {
    flash("There was a problem fetching the results");
}

?>

<?php if (count($results) > 0): ?>
    <div class="list-group">
        <?php foreach ($results as $r): ?>
            <div class="list-group-item">
                <div>
                    <div>Number:</div>
                    <div><?php safer_echo($r["account_number"]); ?></div>
                </div>
                <div>
                    <div>User ID:</div>
                    <div><?php safer_echo($r["user_id"]); ?></div>
                </div>
                <div>
                    <div>Account Type:</div>
                    <div><?php safer_echo($r["account_type"]); ?></div>
                </div>
                <div>
                    <div>Balance:</div>
                    <div><?php safer_echo($r["balance"]); ?></div>
                </div>
                <div>
                    <a type="button" href="test_edit_egg.php?id=<?php safer_echo($r['id']); ?>">Edit</a>
                    <a type="button" href="test_view_egg.php?id=<?php safer_echo($r['id']); ?>">View</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No results</p>
<?php endif; ?>

