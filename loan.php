<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
$query = "";
$results = [];
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
?>

<form method="POST">
	<label>Amount</label>
    <input type="number" name="loan_amount" min=500.00 step=0.01 />
    <input type="submit" name="save" value="Get Loan"/>
    <label for="src">Account: </label>
	<select name="depositAccount" id="deposit">
		<?php foreach ($results as $r): ?>
			<option value=<?php echo($r["id"]);?>><?php safer_echo($r["account_number"]);?></option>
		<?php endforeach; ?>
	</select>
</form>

<?php
if(isset($_POST["save"])){
	$db = getDB();
	$genStmt = $db->prepare("SELECT account_number from Accounts");
	$res = $genStmt->execute();
	echo("Echo\n");
	$result = $genStmt->fetchAll(PDO::FETCH_COLUMN);
	
	$myRandomString = generateRandomString(12);
	while(in_array($myRandomString, $result)){
		$myRandomString = generateRandomString(12);
	}
	
	//TODO add proper validation/checks
	$userID = get_user_id();
	$accountNum = $myRandomString;
	$accountType = "Loan";
    $bal = 0 - $_POST["loan_amount"];
    $apy = 0.06;
	$openDate = date('Y-m-d H:i:s');//calc
	
	$stmt = $db->prepare("INSERT INTO Accounts(account_number, user_id, account_type, opened_date, balance, apy) 
		VALUES(:accountNum, :userID, :accountType, :openDate, :bal, :apy)");

	$r = $stmt->execute([
		":accountNum"=>$accountNum,
		":userID"=>$userID,
		":accountType"=>$accountType,
		":openDate"=>$openDate,
		":bal"=>$bal,
		":apy"=>$apy
	]);

	if($r){
		flash("Created successfully with id: " . $db->lastInsertId());
	}
	else{
		$e = $stmt->errorInfo();
		flash("Error creating: " . var_export($e, true));
    }

    //WRITE THIS IN TRANSACTION HISTORY
	$results = [];
	$source = [];
	$userID = get_user_id();
	$amountValid = true;
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

    $act_src = $world["id"];
    $act_dest = $_POST["depositAccount"];


	$stmtB = $db->prepare("SELECT id, account_number, user_id, account_type, opened_date, balance from Accounts 
		WHERE id=:act_src");
	$rB = $stmtB->execute([":act_src"=>$act_src]);
	if ($rB){
		$source = $stmtB->fetch();
	}
	else{
		flash("There was a problem fetching the results");
	}

	$amount = $_POST["loan_amount"];
	echo($source["balance"]);

	if($amount > $source["balance"]){
		$amountValid = false;
		flash("Amount is greater than source balance.");
	}


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

	//die(header("Location: test_list_accounts.php"));
}

function generateRandomString($length = 12) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>

<?php require(__DIR__ . "/partials/flash.php");