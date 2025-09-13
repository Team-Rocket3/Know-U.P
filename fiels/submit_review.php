<?php
session_start();
header('Content-Type: application/json');
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$booking_id = (int)($_POST['booking_id'] ?? 0);
$rating = (int)($_POST['rating'] ?? 0);
$review_text = $_POST['review_text'] ?? '';

// Validate input
if (!$booking_id || !$rating || $rating < 1 || $rating > 5) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid rating or booking ID.']);
    exit();
}

// Check if booking belongs to user and is completed
$booking_check = $conn->prepare("SELECT id FROM bookings WHERE id = ? AND user_id = ? AND payment_status = 'completed'");
$booking_check->bind_param("ii", $booking_id, $user_id);
$booking_check->execute();
$booking_result = $booking_check->get_result();

if ($booking_result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid booking or booking not completed.']);
    exit();
}

// Check if review already exists
$existing_review = $conn->prepare("SELECT id FROM reviews WHERE booking_id = ? AND user_id = ?");
$existing_review->bind_param("ii", $booking_id, $user_id);
$existing_review->execute();
$existing_result = $existing_review->get_result();

if ($existing_result->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'You have already reviewed this booking.']);
    exit();
}

try {
    // Create reviews table if it doesn't exist
    $create_table = "CREATE TABLE IF NOT EXISTS reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        booking_id INT NOT NULL,
        user_id INT NOT NULL,
        rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
        review_text TEXT,
        destination VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        UNIQUE KEY unique_booking_review (booking_id, user_id)
    )";
    $conn->query($create_table);

    // Get destination from booking
    $get_destination = $conn->prepare("SELECT destination FROM bookings WHERE id = ?");
    $get_destination->bind_param("i", $booking_id);
    $get_destination->execute();
    $dest_result = $get_destination->get_result();
    $destination = $dest_result->fetch_assoc()['destination'] ?? 'General';

    // Insert review
    $insert_review = $conn->prepare("INSERT INTO reviews (booking_id, user_id, rating, review_text, destination, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $insert_review->bind_param("iiiss", $booking_id, $user_id, $rating, $review_text, $destination);

    if ($insert_review->execute()) {
        $_SESSION['success_message'] = 'Review submitted successfully!';
        echo json_encode([
            'status' => 'success', 
            'message' => 'Review submitted successfully!',
            'review_id' => $conn->insert_id,
            'redirect' => true
        ]);
    } else {
        throw new Exception("Failed to submit review: " . $insert_review->error);
    }

    $insert_review->close();

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$conn->close();
?>