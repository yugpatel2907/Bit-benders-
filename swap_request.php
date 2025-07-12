<?php
session_start();
$conn = new mysqli("localhost", "root", "", "hackathon_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Simulated user ID for now
$user_id = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);

    // Insert swap request into DB
    $stmt = $conn->prepare("INSERT INTO swap_requests (product_id, requester_id, status, created_at) VALUES (?, ?, 'pending', NOW())");
    $stmt->bind_param("ii", $product_id, $user_id);

    if ($stmt->execute()) {
        // ✅ Redirect to add_item.php
        header("Location: add_item.php?fromSwap=1&product_id=" . $product_id);
        exit(); // Very important
    } else {
        echo "Swap request failed.";
        exit();
    }

} else {
    // Wrong method, redirect back
    header("Location: dashboard.php");
    exit();
}
?>
