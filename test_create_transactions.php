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
$stmt = $db->prepare("SELECT id, account_number, user_id, account_type, opened_date, balance 
	from Accounts WHERE account_number like :q LIMIT 10");
$r = $stmt->execute([":q" => "%$query%"]);
if ($r) {
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else {
	flash("There was a problem fetching the results");
}
?>

<form method="POST">
	<label for="src">From: </label>
	<select name="account_src" id="src">
		<?php foreach ($results as $r): ?>
			<option value=<?php $r["account_number"]?>><?php safer_echo($r["account_number"]);?></option>
		<?php endforeach; ?>
	</select>

	<label>To: </label>
	<input type="number" name="account_dest"/>

	<label>Amount</label>
	<input type="number" name="amount"/>

	<label>Action</label>
	<input type="text" name="action"/>

	<input type="submit" name="save" value="Create"/>
</form>

<?php
if(isset($_POST["save"])){
	//TODO add proper validation/checks
	$act_src = $_POST["account_src"];
	$act_dest = $_POST["account_dest"];
	$amount = $_POST["amount"];
	$action = $_POST["action"];

	$createdDate = date('Y-m-d H:i:s');//calc
	$db = getDB();

	$stmt = $db->prepare("INSERT INTO Transactions(act_src_id, act_dest_id, amount, action_type, created) 
		VALUES()");
	
	$r = $stmt->execute([
		":accountNum"=>$accountNum,
		":userID"=>$userID,
		":accountType"=>$accountType,
		":openDate"=>$openDate,
		":bal"=>$bal
	]);

	if($r){
		flash("Created successfully with id: " . $db->lastInsertId());
	}
	else{
		$e = $stmt->errorInfo();
		flash("Error creating: " . var_export($e, true));
	}
}
?>
<?php require(__DIR__ . "/partials/flash.php");
