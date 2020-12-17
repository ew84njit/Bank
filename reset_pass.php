<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<form method="POST">
    <!-- DO NOT PRELOAD PASSWORD-->
    <label for="pw">Password</label>
    <input type="password" name="password"/>
    <label for="cpw">Confirm Password</label>
    <input type="password" name="confirm"/>
    <input type="submit" name="saved" value="Save Profile"/>
</form>

