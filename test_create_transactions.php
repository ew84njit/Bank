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
			<option value=<?php echo($r["id"]);?>><?php safer_echo($r["account_number"]);?></option>
		<?php endforeach; ?>
	</select>
	<br></br>
	<label for="dest">To: </label>
	<select name="account_dest" id="dest">
		<?php foreach ($results as $r): ?>
			<option value=<?php echo($r["id"]);?>><?php safer_echo($r["account_number"]);?></option>
		<?php endforeach; ?>
	</select>
	<br></br>
	<label>Amount</label>
	<input type="number" name="amount" min="0.01" step="0.01"/>
	<br></br>
	<label for="action">Action</label>
	<select name="action" id="action">
		<option value="deposit">Deposit</option>
		<option value="withdraw">Withdraw</option>
	</select>
	<br></br>
	<label for="memo">Memo</label>
	<input type="text" name="memo"/>
	<br></br>
	<input type="submit" name="save" value="Create"/>
</form>

<?php
if(isset($_POST["save"])){
	$statement = $db->prepare("SELECT id, account_number, user_id, account_type, opened_date, balance from Accounts 
		WHERE account_number = 000000000000");

	$r = $statement->execute();

	if ($r){
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else{
		flash("There was a problem fetching the results");
	}
	
	$action = $_POST["action"];

	if($action == "deposit") {
		$act_src = safer_echo($r["account_number"]);
		$act_dest = $_POST["account_dest"];
	}
	else if($action == "withdraw") {
		$act_src = $_POST["account_src"];
		$act_dest = $r["account_number"];
	} 
	else {
		$act_src = $_POST["account_src"];
		$act_dest = $_POST["account_dest"];
	}

	$amount = $_POST["amount"];
	
	$balChange = 0 - $amount;
	$createdDate = date('Y-m-d H:i:s');//calc
	$db = getDB();

	//First insertion
	$stmt = $db->prepare("INSERT INTO Transactions(act_src_id, act_dest_id, amount, action_type, balance_change, created) 
		VALUES(:act_src, :act_dest, :amount, :action_type, :balChange, :created)");

	$r = $stmt->execute([
		":act_src"=>$act_src,
		":act_dest"=>$act_dest,
		":amount"=>$amount,
		":action_type"=>$action,
		":balChange"=>$balChange,
		":created"=>$createdDate
	]);

	if($r){
		flash("Created transaction with id: " . $db->lastInsertId());
	}
	else{
		$e = $stmt->errorInfo();
		flash("Error creating: " . var_export($e, true));
	}

	//Second insertion
	$stmt = $db->prepare("INSERT INTO Transactions(act_src_id, act_dest_id, amount, action_type, balance_change, created) 
		VALUES(:act_src, :act_dest, :amount, :action_type, :balChange, :created)");
	$balChange = $amount;
	$r = $stmt->execute([
		":act_src"=>$act_dest,
		":act_dest"=>$act_src,
		":amount"=>$amount,
		":action_type"=>$action,
		":balChange"=>$balChange,
		":created"=>$createdDate
	]);

	if($r){
		flash("Created transaction with id: " . $db->lastInsertId());
	}
	else{
		$e = $stmt->errorInfo();
		flash("Error creating: " . var_export($e, true));
	}
}
?>
<?php require(__DIR__ . "/partials/flash.php");
