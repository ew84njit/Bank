<?php
session_start();
session_unset();
session_destroy();
?>
<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
echo "<script>alert('You are logged out')
window.location.href='login.php';
</script>"; 
//header("Location: login.php");
?>