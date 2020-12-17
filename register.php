<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
if (isset($_POST["register"])) {
    $email = null;
    $firstName = null;
    $lastName = null;
    $password = null;
    $confirm = null;
    $username = null;
    $private = null;
    $disabled = 0;

    if (isset($_POST["email"])) {
        $email = $_POST["email"];
    }
    if (isset($_POST["first"])) {
        $firstName = $_POST["first"];
    }
    if (isset($_POST["last"])) {
        $lastName = $_POST["last"];
    }
    if (isset($_POST["password"])) {
        $password = $_POST["password"];
    }
    if (isset($_POST["confirm"])) {
        $confirm = $_POST["confirm"];
    }
    if (isset($_POST["username"])) {
        $username = $_POST["username"];
    }

    if (isset($_POST["private"])) {
        $private = $_POST["private"];
    } else {
        $private = 0;
    }
    $isValid = true;
    //check if passwords match on the server side
    if ($password == $confirm) {
        echo "Passwords match <br>";
    }
    else {
        echo "Passwords don't match<br>";
        $isValid = false;
    }
    if (!isset($email) || !isset($password) || !isset($confirm)) {
        $isValid = false;
    }
    //TODO other validation as desired, remember this is the last line of defense
    if ($isValid) {
        $hash = password_hash($password, PASSWORD_BCRYPT);

        $db = getDB();
        if (isset($db)) {
            //here we'll use placeholders to let PDO map and sanitize our data
            $stmt = $db->prepare("INSERT INTO BankUsers(email, first_name, last_name, username, password, private, disabled) 
                VALUES(:email, :firstName, :lastName, :username, :password, :private, :disabled)");
            //here's the data map for the parameter to data
            $params = array(":email" => $email, ":firstName" => $firstName, ":lastName" => $lastName, 
                ":username" => $username, ":password" => $hash, ":private" => $private, ":disabled" => $disabled);
            $r = $stmt->execute($params);
            //let's just see what's returned
            echo "db returned: " . var_export($r, true);
            $e = $stmt->errorInfo();
            if ($e[0] == "00000") {
                echo "<br>Welcome! You successfully registered, please login.";
            }
            else {
                if ($e[0] == "23000") {//code for duplicate entry
                    echo "<br>Either username or email is already registered, please try again";
                }
                else {
                    echo "uh oh something went wrong: " . var_export($e, true);
                }
            }
        }
    }
    else {
        echo "There was a validation issue";
    }
}
//safety measure to prevent php warnings
if (!isset($email)) {
    $email = "";
}
if (!isset($username)) {
    $username = "";
}
?>
<form method="POST">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required value="<?php safer_echo($email); ?>"/>

    <label for="first">First Name:</label>
    <input type="text" id="first" name="first" required />

    <label for="last">Last Name:</label>
    <input type="text" id="last" name="last" required />

    <label for="user">Username:</label>
    <input type="text" id="user" name="username" required maxlength="60" value="<?php safer_echo($username); ?>"/>
    <label for="p1">Password:</label>
    <input type="password" id="p1" name="password" required/>
    <label for="p2">Confirm Password:</label>
    <input type="password" id="p2" name="confirm" required/>
    <label for="private">Private:</label>
    <input type="checkbox" name="private" value="1"/>
    <input type="submit" name="register" value="Register"/>
</form>