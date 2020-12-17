<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
$query = "";
$results = [];

if (isset($_POST["query"])) {
    $query = $_POST["query"];
}
if (isset($_POST["search"]) && !empty($query)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT id, account_number, user_id, account_type, opened_date, balance, apy, active
        from Accounts WHERE active != 0 AND account_number like :q LIMIT 10");

    $r = $stmt->execute([":q" => "%$query%"]);

    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        flash("There was a problem fetching the results");
    }
}
?>

<form method="POST">
    <input name="query" placeholder="Search" value="<?php safer_echo($query); ?>"/>
    <input type="submit" value="Search" name="search"/>
</form>

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
                    <div>APY (if applicable):</div>
                    <div><?php safer_echo($r["apy"]); ?></div>
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

