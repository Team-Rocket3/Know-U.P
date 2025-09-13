
<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $booking_id = $_POST['booking_id'];
    
    if ($email && $booking_id) {
        // Generate verification code
        $verification_code = sprintf("%06d", mt_rand(1, 999999));
        
        // Store verification code in database (you'll need to add this table)
        $stmt = $conn->prepare("INSERT INTO email_verifications (booking_id, email, verification_code, created_at) VALUES (?, ?, ?, NOW()) ON DUPLICATE KEY UPDATE verification_code = ?, created_at = NOW()");
        $stmt->bind_param("ssss", $booking_id, $email, $verification_code, $verification_code);
        
        if ($stmt->execute()) {
            // Send email (replace with actual email service)
            $subject = "Know UP - Email Verification Code";
            $message = "Your verification code is: " . $verification_code;
            $headers = "From: noreply@knowup.com";
            
            if (mail($email, $subject, $message, $headers)) {
                echo json_encode(['status' => 'success', 'message' => 'Verification code sent']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to send email']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database error']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email or booking ID']);
    }
}
?>
