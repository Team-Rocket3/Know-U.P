<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'up_tourism';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

// Get reviews from logged-in users with their trip details
$sql = "SELECT r.id, r.rating, r.review_text, 
               COALESCE(r.destination, b.destination, 'General') as destination, 
               r.created_at, 
               u.name as user_name, u.email as user_email,
               b.trip_type
        FROM reviews r 
        JOIN users u ON r.user_id = u.id 
        LEFT JOIN bookings b ON r.booking_id = b.id 
        WHERE r.review_text IS NOT NULL AND r.review_text != ''
        ORDER BY r.created_at DESC 
        LIMIT 20";

$result = $conn->query($sql);
$reviews = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reviews[] = [
            'id' => $row['id'],
            'name' => $row['user_name'],
            'email' => $row['user_email'],
            'destination' => $row['destination'],
            'rating' => (int)$row['rating'],
            'text' => $row['review_text'],
            'trip_type' => $row['trip_type'] ?? 'General',
            'date' => $row['created_at']
        ];
    }
}

$conn->close();
echo json_encode($reviews);
?>