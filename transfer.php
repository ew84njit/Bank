<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
$results = [];
$db = getDB();
$userID = get_user_id();
$stmt = $db->prepare("SELECT id, account_number, user_id, account_type, opened_date, balance 
	from Accounts WHERE user_id = :userID AND frozen = 0");
$r = $stmt->execute([":userID" => $userID]);
if ($r) {
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else {
	flash("There was a problem fetching the results");
}

$results2 = [];
$stmt2 = $db->prepare("SELECT id, account_number, user_id, account_type, opened_date, balance 
	from Accounts WHERE frozen = 0");
$r2 = $stmt2->execute();
if ($r2) {
	$results2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
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
		<?php foreach ($results2 as $r2): ?>
			<option value=<?php echo($r2["id"]);?>><?php safer_echo($r2["account_number"]);?></option>
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
		<option value="transfer">Transfer</option>
	</select>
	<br></br>
	<label for="memo">Memo</label>
	<input type="text" name="memo"/>
	<br></br>
	<input type="submit" name="save" value="Create"/>
</form>

<?php
if(isset($_POST["save"])){
	$db = getDB();
	$results = [];
	$source = [];
	$userID = get_user_id();
	$amountValid = true;
	$stmt = $db->prepare("SELECT id, account_number, user_id, account_type, opened_date, balance from Accounts 
		WHERE account_number = 000000000000");
	$r = $stmt->execute();
	
	if ($r)
	{
		$world = $stmt->fetch();
	}
	else
	{
		flash("There was a problem fetching the results");
	}

	$action = $_POST["action"]; //deposit or withdraw or transfer
    $act_src = $_POST["account_src"];
    $act_dest = $_POST["account_dest"];

	$stmtB = $db->prepare("SELECT id, account_number, user_id, account_type, opened_date, balance from Accounts 
		WHERE id=:act_src");
	$rB = $stmtB->execute([":act_src"=>$act_src]);
	if ($rB){
		$source = $stmtB->fetch();
	}
	else{
		flash("There was a problem fetching the results");
	}

	$amount = $_POST["amount"];
	echo($source["balance"]);

	if($amount > $source["balance"]){
		$amountValid = false;
		flash("Amount is greater than source balance.");
	}
	$memo = $_POST["memo"];

	if($amountValid){
		$balChange = 0 - $amount;
		$createdDate = date('Y-m-d H:i:s');//calc
		

		//FIRST INSERTION
		$stmt = $db->prepare("INSERT INTO Transactions(act_src_id, act_dest_id, amount, action_type, balance_change, created, memo, user_id) 
			VALUES(:act_src, :act_dest, :amount, :action_type, :balChange, :created, :memo, :user_id)");

		$r = $stmt->execute([
			":act_src"=>$act_src,
			":act_dest"=>$act_dest,
			":amount"=>$amount,
			":action_type"=>$action,
			":balChange"=>$balChange,
			":created"=>$createdDate,
			":memo"=>$memo,
			":user_id"=>$userID
		]);

		if($r){
			flash("Created transaction with id: " . $db->lastInsertId());
		}
		else{
			$e = $stmt->errorInfo();
			flash("Error creating: " . var_export($e, true));
		}

		$STH = $db->prepare("UPDATE Accounts SET balance=balance+$balChange WHERE id = $act_src");
		$RH = $STH->execute();
		if($RH){
			flash("Balance updated");
		}
		$RESULTSH = $STH->fetch();

		//SECOND INSERTION
		$stmt = $db->prepare("INSERT INTO Transactions(act_src_id, act_dest_id, amount, action_type, balance_change, created, memo, user_id) 
			VALUES(:act_src, :act_dest, :amount, :action_type, :balChange, :created, :memo, :user_id)");
		$balChange = $amount;
		$r = $stmt->execute([
			":act_src"=>$act_dest,
			":act_dest"=>$act_src,
			":amount"=>$amount,
			":action_type"=>$action,
			":balChange"=>$balChange,
			":created"=>$createdDate,
			":memo"=>$memo,
			":user_id"=>$userID
		]);

		if($r){
			flash("Created transaction with id: " . $db->lastInsertId());
		}
		else{
			$e = $stmt->errorInfo();
			flash("Error creating: " . var_export($e, true));
		}

		$STH = $db->prepare("UPDATE Accounts SET balance=balance+$balChange WHERE id = $act_dest");
		$RH = $STH->execute();
		if($RH){
			flash("Balance updated 2");
		}
		$RESULTSH = $STH->fetch();
	}
}
?>
<?php require(__DIR__ . "/partials/flash.php");