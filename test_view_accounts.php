<?php require_once(__DIR__ . "/partials/nav.php"); ?>


<?php
//we'll put this at the top so both php block have access to it
if (isset($_GET["id"])) {
    $id = $_GET["id"];
}
?>

<?php
$result = [];
if (isset($id)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT account_number, user_id, account_type, balance, active
        FROM Accounts where id = :id AND active != 0");
    
    $r = $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        $e = $stmt->errorInfo();
        flash($e[2]);
    }
}
?>
<?php if (isset($result) && !empty($result)): ?>
    <div class="card">
        <div class="card-body">
            <div>
                <p>Stats</p>
                <div>Account #: <?php safer_echo($result["account_number"]); ?></div>
                <div>User ID: <?php safer_echo($result["user_id"]); ?></div>
                <div>Next Stage: <?php safer_echo($result["account_type"]); ?></div>
                <div>Owned by: <?php safer_echo($result["balance"]); ?></div>
            </div>
        </div>
    </div>
<?php else: ?>
    <p>Error looking up id...</p>
<?php endif; ?>
<?php require(__DIR__ . "/partials/flash.php");
