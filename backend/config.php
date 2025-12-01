<?php

$host = "srv2088.hstgr.io"; 
$db_name = "u600623649_pngtechco_db"; 
$db_user = "u600623649_admin";
$db_pass = "wcc@2025MM";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

