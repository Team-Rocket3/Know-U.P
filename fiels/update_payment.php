<?php
session_start();
require_once "db.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$booking_id = $_POST['booking_id'] ?? '';
$payment_method = $_POST['payment_method'] ?? '';
$payment_status = $_POST['payment_status'] ?? 'completed';

if (empty($booking_id)) {
    echo json_encode(['status' => 'error', 'message' => 'Booking ID is required. Please try booking again.']);
    exit();
}

try {
    // First check if booking exists and belongs to user
    $check_query = "SELECT id, payment_status, payment_method FROM bookings WHERE id = ? AND user_id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("ii", $booking_id, $_SESSION['user_id']);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Booking not found']);
        exit();
    }

    $booking = $result->fetch_assoc();

    // Check if payment data is already the same
    if ($booking['payment_method'] === $payment_method && $booking['payment_status'] === $payment_status) {
        echo json_encode(['status' => 'success', 'message' => 'Payment details already up to date', 'booking_id' => $booking_id]);
        exit();
    }

    // Update payment details
    $query = "UPDATE bookings SET payment_method = ?, payment_status = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssii", $payment_method, $payment_status, $booking_id, $_SESSION['user_id']);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode([
                'status' => 'success', 
                'message' => 'Payment updated successfully', 
                'booking_id' => $booking_id,
                'payment_method' => $payment_method,
                'payment_status' => $payment_status
            ]);
        } else {
            // Log the current booking data for debugging
            error_log("Payment update failed - No rows affected. Booking ID: $booking_id, User ID: {$_SESSION['user_id']}, Current payment_method: {$booking['payment_method']}, Current payment_status: {$booking['payment_status']}, New payment_method: $payment_method, New payment_status: $payment_status");
            echo json_encode(['status' => 'error', 'message' => 'Payment update failed - booking may already be processed or data unchanged']);
        }
    } else {
        error_log("Payment update query failed: " . $stmt->error);
        echo json_encode(['status' => 'error', 'message' => 'Database error occurred during payment update']);
    }

    $stmt->close();
    $check_stmt->close();
} catch (Exception $e) {
    error_log("Exception during payment update: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred.']);
}

$conn->close();
?>