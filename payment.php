<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
$query = "";
$results = [];
$resultsLoan = [];
$loanStr = "Loan";
$db = getDB();
$userID = get_user_id();
$stmt = $db->prepare("SELECT id, account_number, user_id, account_type, opened_date, balance 
	from Accounts WHERE user_id = :userID");
$r = $stmt->execute([":userID" => $userID]);
if ($r) {
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else {
	flash("There was a problem fetching the results");
}

//Getting loan accounts
$stmt = $db->prepare("SELECT id, account_number, user_id, account_type, opened_date, balance 
    from Accounts WHERE user_id = :userID AND account_type= :loan");
$rLoan = $stmt->execute([":userID" => $userID, ":loan" => $loanStr]);
if ($r) {
	$resultsLoan = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else {
	flash("There was a problem fetching the results");
}
?>

<form METHOD="POST">
    <label>Amount</label>
    <input type="number" name="amount" min=0.01 step=0.01 />

    <label for="src">Account: </label>
	<select name="src" id="src">
		<?php foreach ($results as $r): ?>
			<option value=<?php echo($r["id"]);?>><?php safer_echo($r["account_number"]);?></option>
		<?php endforeach; ?>
    </select>

    <label for="loan">Loan To Pay Off: </label>

	<select name="loan" id="loan">
		<?php foreach ($resultsLoan as $r): ?>
			<option value=<?php echo($r["id"]);?>><?php safer_echo($r["account_number"]);?></option>
		<?php endforeach; ?>
    </select>

    <input type="submit" name="save" value="Pay Loan"/>
</form>

<?php
if(isset($_POST["save"])){
	$db = getDB();
	
	//TODO add proper validation/checks
	$userID = get_user_id();
    $accountType = "Loan";
    $apy = 0.06;
    $bal = 0 - ($_POST["amount"] * (1+$apy));
    
	$openDate = date('Y-m-d H:i:s');//calc


    //WRITE THIS IN TRANSACTION HISTORY
	$results = [];
	$source = [];
    $userID = get_user_id();
    
    $amountValid = true; //Check if amount is greater than source balance
    
	$stmt = $db->prepare("SELECT id, account_number, user_id, account_type, opened_date, balance from Accounts 
		WHERE account_number = 000000000000");
	$r = $stmt->execute();
	
	if ($r)
	{
		//$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$world = $stmt->fetch();
	}
	else
	{
		flash("There was a problem fetching the results");
	}

    $act_src = $_POST["src"];
    $act_dest = $_POST["loan"];


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
    $action = "Debt Payment";

    if($amount > $source["balance"]){
        $amountValid = false;
        echo("Account not enough balance");
    }

	if($amountValid){
        //FIRST INSERTION
		$balChange = 0 - $amount;
		$createdDate = date('Y-m-d H:i:s');//calc
		
		$stmt = $db->prepare("INSERT INTO Transactions(act_src_id, act_dest_id, amount, action_type, balance_change, created, memo, user_id) 
			VALUES(:act_src, :act_dest, :amount, :action_type, :balChange, :created, :memo, :user_id)");

		$r = $stmt->execute([
			":act_src"=>$act_src,
			":act_dest"=>$act_dest,
			":amount"=>$amount,
			":action_type"=>$action,
			":balChange"=>$balChange,
			":created"=>$createdDate,
			":memo"=>NULL,
			":user_id"=>$userID
		]);

		if($r){flash("Created transaction with id: " . $db->lastInsertId());}
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
			":memo"=>NULL,
			":user_id"=>$userID
		]);
		if($r){flash("Created transaction with id: " . $db->lastInsertId());}
		else{
			$e = $stmt->errorInfo();
			flash("Error creating: " . var_export($e, true));
		}
		$STH = $db->prepare("UPDATE Accounts SET balance=balance+$balChange WHERE id = $act_dest");
		$RH = $STH->execute();
		if($RH){flash("Balance updated 2");}
		$RESULTSH = $STH->fetch();
	}

	//die(header("Location: test_list_accounts.php"));
}
?>
<?php require(__DIR__ . "/partials/flash.php");?>