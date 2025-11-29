<?php
$host = "srv2088.hstgr.io";  // Hostinger database host
$db_name = "u600623649_pngtechco_db";       // your actual DB name
$db_user = "u600623649_pngtechco_admi";       // your DB username (same prefix)
$db_pass = "wcc@2025MM";            // the password you created

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

