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

$db = getDB();
$query = "SELECT id, account_number, user_id, account_type, opened_date, balance from Accounts";
if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_row()) {
        printf("%s (%s,%s)\n", $row[0], $row[1], $row[2]);
    }
    /* free result set */
    $result->close();
}

//$r = $stmt->execute();

if ($r) {
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                    <a type="button" href="test_edit_accounts.php?id=<?php safer_echo($r['id']); ?>">Edit</a>
                    <a type="button" href="test_view_accounts.php?id=<?php safer_echo($r['id']); ?>">View</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No results</p>
<?php endif; ?>

