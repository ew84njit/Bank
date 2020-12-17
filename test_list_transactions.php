<?php require_once(__DIR__ . "/partials/nav.php"); ?>

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

$stmt = $db->prepare("SELECT id, act_src_id, act_dest_id, amount, action_type, memo, balance_change, created, user_id
    from Transactions WHERE user_id=:user_id LIMIT 10");
$r = $stmt->execute(["user_id"=>$user_id]);

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
                    <div>From:</div>
                    <div><?php safer_echo($r["act_src_id"]); ?></div>
                </div>
                <div>
                    <div>To:</div>
                    <div><?php safer_echo($r["act_dest_id"]); ?></div>
                </div>
                <div>
                    <div>Amount:</div>
                    <div><?php safer_echo($r["amount"]); ?></div>
                </div>
                <div>
                    <div>Type:</div>
                    <div><?php safer_echo($r["action_type"]); ?></div>
                </div>
                <div>
                    <div>Memo:</div>
                    <div><?php safer_echo($r["memo"]); ?></div>
                </div>
                <div>
                    <div>Balance Change:</div>
                    <div><?php safer_echo($r["balance_change"]); ?></div>
                </div>
                <div>
                    <div>Date:</div>
                    <div><?php safer_echo($r["created"]); ?></div>
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

