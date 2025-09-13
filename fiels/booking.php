<?php
session_start();

// Set content type to JSON for API responses
header('Content-Type: application/json');
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Get form data
$city = $_POST['city'] ?? '';
$checkin_date = $_POST['checkin_date'] ?? '';
$checkout_date = $_POST['checkout_date'] ?? '';
$adults = (int)($_POST['adults'] ?? 1);
$children = (int)($_POST['children'] ?? 0);
$trip_type = $_POST['trip_type'] ?? '';
$special_requests = $_POST['special_requests'] ?? '';
$payment_method = $_POST['payment_method'] ?? '';
$payment_amount = (float)($_POST['payment_amount'] ?? 0);
$payment_status = $_POST['payment_status'] ?? 'pending';

// Validate required fields
if (empty($city) || empty($checkin_date) || empty($checkout_date) || empty($trip_type)) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
    exit();
}

// Validate dates
$checkin = new DateTime($checkin_date);
$checkout = new DateTime($checkout_date);
$today = new DateTime();
$today->setTime(0, 0, 0); // Set to start of day for fair comparison

if ($checkin < $today) {
    echo json_encode(['status' => 'error', 'message' => 'Check-in date cannot be in the past.']);
    exit();
}

if ($checkout <= $checkin) {
    echo json_encode(['status' => 'error', 'message' => 'Check-out date must be after check-in date.']);
    exit();
}

// Use the payment amount provided from frontend, don't recalculate
// This ensures the amount matches what user sees in the booking modal


try {
    // Insert booking into database
    $query = "INSERT INTO bookings (user_id, destination, check_in, check_out, adults, children, trip_type, special_requests, payment_method, payment_amount, payment_status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($query);

    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }

    $stmt->bind_param("isssiisssds", $user_id, $city, $checkin_date, $checkout_date, $adults, $children, $trip_type, $special_requests, $payment_method, $payment_amount, $payment_status);

    if ($stmt->execute()) {
        $booking_id = $conn->insert_id;
        
        // Booking created successfully - no additional status update needed

        echo json_encode([
            'status' => 'success',
            'message' => 'Booking created successfully! Please complete verification.',
            'booking_id' => $booking_id,
            'requires_verification' => true
        ]);
    } else {
        throw new Exception("Failed to create booking: " . $stmt->error);
    }

    $stmt->close();

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$conn->close();
?>