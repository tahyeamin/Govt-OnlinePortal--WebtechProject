<?php
session_start();
if (!isset($_SESSION["uname"])) {
    header("Location: index.php");
    exit();
}

// Retrieve the form data
$insurance_type = $_POST['insurance_type'];
$amount = $_POST['amount'];
$start_date = $_POST['start_date'];
$duration = $_POST['duration'];

// Create a unique receipt ID (could be based on current time or a random string)
$receipt_id = "INS" . time();

// Store the insurance data in the session
$insurance_record = [
    'receipt_id' => $receipt_id,
    'insurance_type' => $insurance_type,
    'amount' => $amount,
    'start_date' => $start_date,
    'duration' => $duration,
    'date' => date('Y-m-d H:i:s')
];

if (!isset($_SESSION['insurance_history'])) {
    $_SESSION['insurance_history'] = [];
}

// Add the new record to the history
$_SESSION['insurance_history'][] = $insurance_record;

// Set the insurance history cookie (store up to 7 records in the cookie)
$history = isset($_COOKIE['insurance_history']) ? json_decode($_COOKIE['insurance_history'], true) : [];
$history[] = $insurance_record;
setcookie('insurance_history', json_encode($history), time() + (86400 * 30), "/");  // Expiry in 30 days

// Redirect to the history page
header("Location: history.php");
exit();
?>
