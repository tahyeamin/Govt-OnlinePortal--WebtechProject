<?php
session_start();
if (!isset($_SESSION["uname"])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tax_amount = $_POST["tax_amount"];
    $payment_date = $_POST["payment_date"];
    $payment_method = $_POST["payment_method"];
    $receipt_id = uniqid("tax_");

    // Store tax payment in session history
    $tax_history = isset($_SESSION["tax_history"]) ? $_SESSION["tax_history"] : [];
    $tax_history[] = [
        "receipt_id" => $receipt_id,
        "tax_amount" => $tax_amount,
        "payment_date" => $payment_date,
        "payment_method" => $payment_method,
        "date" => date("Y-m-d H:i:s")
    ];
    $_SESSION["tax_history"] = $tax_history;

    // Create a cookie for the tax payment
    $cookie_data = json_encode([
        "receipt_id" => $receipt_id,
        "tax_amount" => $tax_amount,
        "payment_date" => $payment_date,
        "payment_method" => $payment_method,
        "date" => date("Y-m-d H:i:s")
    ]);

    // Set the cookie (expires in 1 week)
    setcookie("tax_payment_" . $receipt_id, $cookie_data, time() + (7 * 24 * 60 * 60), "/");

    // Redirect back to the history page
    header("Location: history.php");
    exit();
}
?>
