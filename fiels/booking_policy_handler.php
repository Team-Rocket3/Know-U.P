
<?php
class BookingPolicyHandler {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    // Calculate cancellation charges based on policy
    public function calculateCancellationCharges($booking_id) {
        $sql = "SELECT checkin_date, final_amount, booking_date FROM bookings WHERE id = ?";
        $stmt = executeQuery($this->conn, $sql, "i", [$booking_id]);
        
        if (!$stmt) {
            return ['error' => 'Booking not found'];
        }
        
        $result = $stmt->get_result();
        $booking = $result->fetch_assoc();
        $stmt->close();
        
        if (!$booking) {
            return ['error' => 'Booking not found'];
        }
        
        $checkin_date = new DateTime($booking['checkin_date']);
        $current_date = new DateTime();
        $days_until_travel = $current_date->diff($checkin_date)->days;
        $total_amount = $booking['final_amount'];
        
        // Calculate charges based on policy
        if ($days_until_travel > 30) {
            $charge_percentage = 10;
        } elseif ($days_until_travel >= 15) {
            $charge_percentage = 25;
        } elseif ($days_until_travel >= 7) {
            $charge_percentage = 50;
        } elseif ($days_until_travel >= 3) {
            $charge_percentage = 75;
        } else {
            $charge_percentage = 100;
        }
        
        $cancellation_charge = ($total_amount * $charge_percentage) / 100;
        $refund_amount = $total_amount - $cancellation_charge;
        
        return [
            'charge_percentage' => $charge_percentage,
            'cancellation_charge' => $cancellation_charge,
            'refund_amount' => $refund_amount,
            'days_until_travel' => $days_until_travel
        ];
    }
    
    // Apply discount codes
    public function applyDiscountCode($code, $total_amount, $user_id) {
        $discount_amount = 0;
        $discount_percentage = 0;
        $valid = false;
        
        switch (strtoupper($code)) {
            case 'STUDENT10':
                if ($this->verifyStudentStatus($user_id)) {
                    $discount_percentage = 10;
                    $valid = true;
                }
                break;
            case 'SENIOR15':
                if ($this->verifySeniorStatus($user_id)) {
                    $discount_percentage = 15;
                    $valid = true;
                }
                break;
            case 'FIRSTTIME5':
                if ($this->isFirstTimeUser($user_id)) {
                    $discount_percentage = 5;
                    $valid = true;
                }
                break;
        }
        
        if ($valid) {
            $discount_amount = ($total_amount * $discount_percentage) / 100;
        }
        
        return [
            'valid' => $valid,
            'discount_percentage' => $discount_percentage,
            'discount_amount' => $discount_amount,
            'final_amount' => $total_amount - $discount_amount
        ];
    }
    
    // Check document requirements for destinations
    public function checkDocumentRequirements($destination) {
        $requirements = [
            'domestic' => [
                'Valid Photo ID (Aadhaar Card, PAN Card, Passport, Driving License)',
                'Vaccination certificate (if required)',
                'Special permits for certain destinations'
            ],
            'international' => [
                'Valid passport with minimum 6 months validity',
                'Appropriate visa for destination country',
                'International vaccination certificates',
                'Travel insurance (mandatory for some countries)'
            ]
        ];
        
        // For now, all UP destinations are domestic
        return $requirements['domestic'];
    }
    
    // Verify if user is a student
    private function verifyStudentStatus($user_id) {
        // This would typically check against uploaded student ID
        // For demo purposes, returning true
        return true;
    }
    
    // Verify if user is senior citizen
    private function verifySeniorStatus($user_id) {
        // This would typically check user's age
        // For demo purposes, returning true
        return true;
    }
    
    // Check if user is first time visitor
    private function isFirstTimeUser($user_id) {
        $sql = "SELECT COUNT(*) as booking_count FROM bookings WHERE user_id = ? AND status = 'confirmed'";
        $stmt = executeQuery($this->conn, $sql, "i", [$user_id]);
        
        if (!$stmt) {
            return false;
        }
        
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row['booking_count'] == 0;
    }
    
    // Process modification requests
    public function processModification($booking_id, $new_checkin, $new_checkout, $modification_type) {
        $modification_fee = 500; // Per person as per policy
        
        // Get current booking details
        $sql = "SELECT * FROM bookings WHERE id = ?";
        $stmt = executeQuery($this->conn, $sql, "i", [$booking_id]);
        
        if (!$stmt) {
            return ['success' => false, 'message' => 'Booking not found'];
        }
        
        $result = $stmt->get_result();
        $booking = $result->fetch_assoc();
        $stmt->close();
        
        if (!$booking) {
            return ['success' => false, 'message' => 'Booking not found'];
        }
        
        // Check if modification is allowed (within 15 days policy)
        $checkin_date = new DateTime($booking['checkin_date']);
        $current_date = new DateTime();
        $days_until_travel = $current_date->diff($checkin_date)->days;
        
        if ($days_until_travel < 15) {
            return ['success' => false, 'message' => 'Modifications not allowed within 15 days of travel'];
        }
        
        // Calculate new amount if dates changed
        $new_checkin_dt = new DateTime($new_checkin);
        $new_checkout_dt = new DateTime($new_checkout);
        $new_days = $new_checkin_dt->diff($new_checkout_dt)->days;
        $old_days = $booking['days_count'];
        
        $price_difference = ($new_days - $old_days) * $booking['base_rate'] * $booking['adults'];
        $total_modification_fee = $modification_fee * ($booking['adults'] + $booking['children']);
        $new_total = $booking['final_amount'] + $price_difference + $total_modification_fee;
        
        // Update booking
        $update_sql = "UPDATE bookings SET 
                       checkin_date = ?, 
                       checkout_date = ?, 
                       days_count = ?, 
                       final_amount = ?,
                       modification_fee = ?,
                       modified_date = NOW()
                       WHERE id = ?";
        
        $update_stmt = executeQuery($this->conn, $update_sql, "ssiidi", [
            $new_checkin, $new_checkout, $new_days, $new_total, $total_modification_fee, $booking_id
        ]);
        
        if ($update_stmt) {
            $update_stmt->close();
            return [
                'success' => true, 
                'message' => 'Booking modified successfully',
                'modification_fee' => $total_modification_fee,
                'price_difference' => $price_difference,
                'new_total' => $new_total
            ];
        } else {
            return ['success' => false, 'message' => 'Failed to update booking'];
        }
    }
}
?>
