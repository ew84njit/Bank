<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}

$db = getDB();
$myRandomString = generateRandomString(12);
$genStmt = $db->prepare("SELECT account_number from Accounts");
$res = $genStmt->execute();
echo("Echo\n");
$result = $genStmt->fetchAll(PDO::FETCH_COLUMN);
//print_r($result);
foreach($result as $num){
	echo($num);
	echo("\n");
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
	$myRandomString = generateRandomString(12);
	$genStmt = $db->prepare("SELECT account_number from Accounts");
	$res = $genStmt->execute();
	echo("Echo\n");
	$result = $genStmt->fetchAll(PDO::FETCH_COLUMN);
	//print_r($result);
	foreach($result as $num){
		echo($num);
		echo(\n);
	}

	//TODO add proper validation/checks
	$name = $_POST["name"];
	$accountNum = 111111111101;
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

function generateRandomString($length = 12) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
//usage 

?>
<?php require(__DIR__ . "/partials/flash.php");
