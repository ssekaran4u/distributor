<?php
session_start();
session_destroy();
session_unset();
unset($_SESSION["username"]);
unset($_SESSION["position"]);
header("Location:index.php");
?>