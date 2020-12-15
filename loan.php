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
    <input type="text" name="loan_amount" min=500.00 step=0.01 />
    <input type="submit" name="save" value="Get Loan"/>
    <label for="src">From: </label>
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

	die(header("Location: test_list_accounts.php"));
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