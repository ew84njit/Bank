<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>

<form method="POST">
	<label>Name</label>
	<input name="name" placeholder="Name"/>

	<label>Account Type</label>
	<input type="text" name="account_type"/>
	
	<label>Balance</label>
	<input type="text" name="bal"/>

	<input type="submit" name="save" value="Create"/>
</form>

<?php
if(isset($_POST["save"])){
	$db = getDB();

	$generate = $db->prepare("SELECT random_num
		FROM (SELECT FLOOR(RAND() * 999999999999) AS random_num) AS Accounts_Plus_1
		WHERE random_num NOT IN (SELECT account_number FROM Accounts WHERE account_number IS NOT NULL)
		LIMIT 1"
	);
	$accountNum = $generate->execute();

	//TODO add proper validation/checks
	$name = $_POST["name"];
	//$accountNum = $_POST["account_number"];
	$userID = get_user_id();
	$accountType = $_POST["account_type"];
	$bal = $_POST["bal"];

	$openDate = date('Y-m-d H:i:s');//calc
	

	$stmt = $db->prepare("INSERT INTO Accounts(account_number, user_id, account_type, opened_date, balance) 
		VALUES(:accountNum, :userID, :accountType, :openDate, :bal)");

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
