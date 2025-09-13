
<?php
session_start();

header('Content-Type: application/json');
require_once 'db.php'; 

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$booking_id = $_POST['booking_id'] ?? null;
$action = $_POST['action'] ?? null;

if (!$booking_id || $action !== 'cancel') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    exit;
}

// Check if booking belongs to user
$check_query = "SELECT id, payment_status FROM bookings WHERE id = ? AND user_id = ?";
$check_stmt = $conn->prepare($check_query);

if (!$check_stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
    exit;
}

$check_stmt->bind_param("ii", $booking_id, $user_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Booking not found or access denied.']);
    exit;
}

$booking = $result->fetch_assoc();

// Delete the booking
$delete_query = "DELETE FROM bookings WHERE id = ? AND user_id = ?";
$delete_stmt = $conn->prepare($delete_query);

if (!$delete_stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
    exit;
}

$delete_stmt->bind_param("ii", $booking_id, $user_id);

if ($delete_stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Booking cancelled successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to cancel booking.']);
}

$delete_stmt->close();
$check_stmt->close();
$conn->close();
?>
