
<?php
session_start();
include 'db.php';
include 'booking_policy_handler.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$handler = new BookingPolicyHandler($conn);

// Get user's bookings
$query = "SELECT * FROM bookings WHERE user_id = ? ORDER BY booking_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$bookings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - Know UP</title>
    <link rel="icon" href="../ExploreUP/photo/taj-mahal.png" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .header h1 {
            color: #2563eb;
            margin-bottom: 10px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            margin-bottom: 20px;
            transition: background 0.3s ease;
        }

        .back-btn:hover {
            background: #1d4ed8;
        }

        .booking-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .booking-card:hover {
            transform: translateY(-2px);
        }

        .booking-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 16px;
        }

        .booking-id {
            font-weight: bold;
            color: #2563eb;
            font-size: 18px;
        }

        .booking-status {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .status-confirmed {
            background: #dcfce7;
            color: #166534;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #dc2626;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .booking-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-item i {
            color: #2563eb;
            width: 16px;
        }

        .booking-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        .btn-warning {
            background: #f59e0b;
            color: white;
        }

        .btn-info {
            background: #0891b2;
            color: white;
        }

        .btn:hover {
            transform: translateY(-1px);
            opacity: 0.9;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 16px;
            width: 90%;
            max-width: 600px;
            position: relative;
        }

        .close {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 24px;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .alert-info {
            background: #e0f2fe;
            border: 1px solid #0891b2;
            color: #0e7490;
        }

        .alert-warning {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
        }

        .alert-danger {
            background: #fee2e2;
            border: 1px solid #dc2626;
            color: #dc2626;
        }

        .cancellation-calculator {
            background: #f8fafc;
            padding: 20px;
            border-radius: 12px;
            margin: 16px 0;
        }

        .calc-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .calc-total {
            font-weight: bold;
            font-size: 18px;
            border-top: 1px solid #d1d5db;
            padding-top: 8px;
        }

        @media (max-width: 768px) {
            .booking-details {
                grid-template-columns: 1fr;
            }

            .booking-header {
                flex-direction: column;
                gap: 12px;
                text-align: center;
            }

            .booking-actions {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="../index.html" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Home
        </a>

        <div class="header">
            <h1><i class="fas fa-clipboard-list"></i> Manage Your Bookings</h1>
            <p>View, modify, or cancel your travel bookings</p>
        </div>

        <?php if (empty($bookings)): ?>
            <div class="booking-card">
                <div style="text-align: center; padding: 40px;">
                    <i class="fas fa-calendar-times" style="font-size: 48px; color: #9ca3af; margin-bottom: 16px;"></i>
                    <h3 style="color: #6b7280; margin-bottom: 8px;">No Bookings Found</h3>
                    <p style="color: #9ca3af;">You haven't made any bookings yet. Start exploring Uttar Pradesh!</p>
                    <a href="../index.html" class="btn btn-primary" style="margin-top: 20px;">
                        <i class="fas fa-search"></i>
                        Browse Destinations
                    </a>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($bookings as $booking): ?>
                <div class="booking-card">
                    <div class="booking-header">
                        <div class="booking-id">
                            <i class="fas fa-hashtag"></i> Booking ID: <?= $booking['id'] ?>
                        </div>
                        <div class="booking-status status-<?= $booking['status'] ?>">
                            <?= ucfirst($booking['status']) ?>
                        </div>
                    </div>

                    <div class="booking-details">
                        <div class="detail-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <strong>Destination:</strong><br>
                                <?= htmlspecialchars($booking['city']) ?>
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-calendar"></i>
                            <div>
                                <strong>Check-in:</strong><br>
                                <?= date('d M Y', strtotime($booking['checkin_date'])) ?>
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-calendar-check"></i>
                            <div>
                                <strong>Check-out:</strong><br>
                                <?= date('d M Y', strtotime($booking['checkout_date'])) ?>
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-users"></i>
                            <div>
                                <strong>Guests:</strong><br>
                                <?= $booking['adults'] ?> Adults, <?= $booking['children'] ?> Children
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-rupee-sign"></i>
                            <div>
                                <strong>Amount:</strong><br>
                                ₹<?= number_format($booking['final_amount'] ?: $booking['total_amount']) ?>
                                <?php if ($booking['discount_amount'] > 0): ?>
                                    <small style="color: #16a34a;">(<?= $booking['discount_type'] ?> discount applied)</small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-tag"></i>
                            <div>
                                <strong>Trip Type:</strong><br>
                                <?= ucfirst(str_replace('_', ' ', $booking['trip_type'])) ?>
                            </div>
                        </div>
                    </div>

                    <?php if ($booking['special_requests']): ?>
                        <div class="alert alert-info">
                            <strong><i class="fas fa-info-circle"></i> Special Requests:</strong><br>
                            <?= htmlspecialchars($booking['special_requests']) ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($booking['status'] === 'confirmed'): ?>
                        <div class="booking-actions">
                            <button class="btn btn-info" onclick="viewDetails(<?= $booking['id'] ?>)">
                                <i class="fas fa-eye"></i> View Details
                            </button>
                            <button class="btn btn-warning" onclick="modifyBooking(<?= $booking['id'] ?>)">
                                <i class="fas fa-edit"></i> Modify Dates
                            </button>
                            <button class="btn btn-danger" onclick="cancelBooking(<?= $booking['id'] ?>)">
                                <i class="fas fa-times"></i> Cancel Booking
                            </button>
                            <button class="btn btn-primary" onclick="downloadVoucher(<?= $booking['id'] ?>)">
                                <i class="fas fa-download"></i> Download Voucher
                            </button>
                        </div>
                    <?php elseif ($booking['status'] === 'cancelled'): ?>
                        <div class="alert alert-danger">
                            <strong><i class="fas fa-exclamation-triangle"></i> Cancelled on:</strong> 
                            <?= date('d M Y', strtotime($booking['cancellation_date'])) ?><br>
                            <?php if ($booking['refund_amount'] > 0): ?>
                                <strong>Refund Amount:</strong> ₹<?= number_format($booking['refund_amount']) ?>
                                <small>(Processing time: 7-10 working days)</small>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Cancellation Modal -->
    <div id="cancelModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('cancelModal')">&times;</span>
            <h2><i class="fas fa-exclamation-triangle"></i> Cancel Booking</h2>
            
            <div id="cancellationCalculator" class="cancellation-calculator" style="display: none;">
                <h4>Cancellation Charges</h4>
                <div class="calc-row">
                    <span>Original Amount:</span>
                    <span id="originalAmount">₹0</span>
                </div>
                <div class="calc-row">
                    <span>Cancellation Charge (<span id="chargePercentage">0</span>%):</span>
                    <span id="cancellationCharge">₹0</span>
                </div>
                <div class="calc-row calc-total">
                    <span>Refund Amount:</span>
                    <span id="refundAmount">₹0</span>
                </div>
                <div class="alert alert-warning">
                    <small><i class="fas fa-info-circle"></i> Refund will be processed to your original payment method within 7-10 working days.</small>
                </div>
            </div>

            <form id="cancelForm">
                <input type="hidden" id="cancelBookingId" name="booking_id">
                <div class="form-group">
                    <label for="cancelReason">Reason for Cancellation:</label>
                    <select id="cancelReason" name="reason" required>
                        <option value="">Select a reason</option>
                        <option value="change_of_plans">Change of Plans</option>
                        <option value="medical_emergency">Medical Emergency</option>
                        <option value="work_commitment">Work Commitment</option>
                        <option value="weather_conditions">Weather Conditions</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cancelComments">Additional Comments (Optional):</label>
                    <textarea id="cancelComments" name="comments" rows="3" placeholder="Please provide any additional details..."></textarea>
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-check"></i> Confirm Cancellation
                    </button>
                    <button type="button" class="btn" style="background: #6b7280; color: white; margin-left: 12px;" onclick="closeModal('cancelModal')">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modify Booking Modal -->
    <div id="modifyModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modifyModal')">&times;</span>
            <h2><i class="fas fa-edit"></i> Modify Booking Dates</h2>
            
            <div class="alert alert-warning">
                <i class="fas fa-info-circle"></i> Date changes allowed up to 15 days before travel. Modification charge: ₹500 per person.
            </div>

            <form id="modifyForm">
                <input type="hidden" id="modifyBookingId" name="booking_id">
                <div class="form-group">
                    <label for="newCheckin">New Check-in Date:</label>
                    <input type="date" id="newCheckin" name="new_checkin" required>
                </div>
                <div class="form-group">
                    <label for="newCheckout">New Check-out Date:</label>
                    <input type="date" id="newCheckout" name="new_checkout" required>
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-check"></i> Confirm Changes
                    </button>
                    <button type="button" class="btn" style="background: #6b7280; color: white; margin-left: 12px;" onclick="closeModal('modifyModal')">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function cancelBooking(bookingId) {
            document.getElementById('cancelBookingId').value = bookingId;
            
            // Calculate cancellation charges
            fetch('booking_policy_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=calculate_cancellation&booking_id=${bookingId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' && data.charges) {
                    const charges = data.charges;
                    document.getElementById('originalAmount').textContent = `₹${charges.total_amount || 0}`;
                    document.getElementById('chargePercentage').textContent = charges.charge_percentage;
                    document.getElementById('cancellationCharge').textContent = `₹${charges.cancellation_charge || 0}`;
                    document.getElementById('refundAmount').textContent = `₹${charges.refund_amount || 0}`;
                    document.getElementById('cancellationCalculator').style.display = 'block';
                }
            });
            
            document.getElementById('cancelModal').style.display = 'block';
        }

        function modifyBooking(bookingId) {
            document.getElementById('modifyBookingId').value = bookingId;
            
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('newCheckin').min = today;
            
            document.getElementById('modifyModal').style.display = 'block';
        }

        function viewDetails(bookingId) {
            window.open(`booking_details.php?id=${bookingId}`, '_blank');
        }

        function downloadVoucher(bookingId) {
            window.open(`download_voucher.php?id=${bookingId}`, '_blank');
        }

        // Handle cancellation form submission
        document.getElementById('cancelForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'cancel_booking');
            
            fetch('booking_policy_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Booking cancelled successfully. Refund will be processed within 7-10 working days.');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Unable to cancel booking'));
                }
            })
            .catch(error => {
                alert('An error occurred. Please try again.');
            });
        });

        // Handle modification form submission
        document.getElementById('modifyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'modify_dates');
            
            fetch('booking_policy_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Booking dates modified successfully. Modification charges apply.');
                    location.reload();
                } else {
                    alert('Error: Unable to modify booking dates');
                }
            })
            .catch(error => {
                alert('An error occurred. Please try again.');
            });
        });

        // Set checkout date minimum when checkin changes
        document.getElementById('newCheckin').addEventListener('change', function() {
            const checkinDate = new Date(this.value);
            checkinDate.setDate(checkinDate.getDate() + 1);
            document.getElementById('newCheckout').min = checkinDate.toISOString().split('T')[0];
        });

        // Close modals when clicking outside
        window.addEventListener('click', function(event) {
            const modals = ['cancelModal', 'modifyModal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
