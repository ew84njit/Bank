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
if (isset($_POST["search"]) && !empty($query)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT id, username, email, first_name, last_name
        from BankUsers WHERE first_name like :q OR last_name like :q LIMIT 10");

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
                <div><p class="font-weight-bold"> User:</p>
                <?php safer_echo($r["username"]); ?>
                </div>
                <div><p class="font-weight-bold"> Email:</p>
                <?php safer_echo($r["email"]); ?>
                </div>
                <div><p class="font-weight-bold"> Name:</p>
                <?php safer_echo($r["last_name"]); ?>, <?php safer_echo($r["first_name"]); ?>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No results</p>
<?php endif; ?>