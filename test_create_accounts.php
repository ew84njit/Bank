<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>

<script>
$("#actType").change(function () {
	var selected = $("#actType option:selected").form();
	if ($(this).val() == "Savings") {
		$('#Savings').show();
		$('#savingsForm').attr('required', '');
    	$('#savingsForm').attr('data-error', 'This field is required.');
  	} else {
		$('#Savings').hide();
		$('#savingsForm').removeAttr('required');
    	$('#savingsForm').removeAttr('data-error');
  	}
	//$('div').hide();
	//$('#' + selected).show();
});
$("#actType").trigger("change");

</script>
<?php
/*
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
*/
?>

<form method="POST">
	<label>Name</label>
	<input name="name" placeholder="Name"/>

	<label>Account Type</label>
	<select id="account_type" name="account_type" id="actType">
		<option value="Checking">Checking</option>
		<option value="Savings">Savings</option>
	</select>
	<label>Balance</label>
	<input type="text" name="bal"/>

	<div id="Savings">
		<label>APY</label>
		<input type="text" name="apy" id="savingsForm"/>
	</div>
	

	<input type="submit" name="save" value="Create"/>
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
	$name = $_POST["name"];
	
	$userID = get_user_id();
	$accountNum = $myRandomString;
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
