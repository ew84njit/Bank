<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
$query = "";
$results = [];
$db = getDB();
$userID = get_user_id();
$stmt = $db->prepare("SELECT id, account_number, user_id, account_type, opened_date, balance 
	from Accounts WHERE user_id = :userID");
$r = $stmt->execute([":userID" => $userID]);
if ($r) 
{
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else 
{
	flash("There was a problem fetching the results");
}
?>

<form method="POST">
    The Account has to have a balance of 0, before closing.
    <label for="src">Select Account To Close: </label>
	<select name="deleteAccount" id="deleteAccount">
		<?php foreach ($results as $r): ?>
			<option value=<?php echo($r["id"]);?>><?php safer_echo($r["account_number"]);?></option>
		<?php endforeach; ?>
    </select>
    <input type="submit" name="save" value="Close Account"/>
</form>

<?php
if(isset($_POST["save"])){
    $deleteID = $_POST["deleteAccount"];
    $stmt = $db->prepare("DELETE FROM Accounts WHERE id = :deleteID");
    $r = $stmt->execute([":deleteID" => $deleteID]);
}
?>