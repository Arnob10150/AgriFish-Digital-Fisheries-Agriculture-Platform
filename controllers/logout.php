<?php
session_start();
session_unset();
session_destroy();
header("Location: /DFAP/customer/views/login.php");
exit;
?>