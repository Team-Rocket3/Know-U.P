<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user information
$user_query = $conn->prepare("SELECT * FROM users WHERE id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user_result = $user_query->get_result();
$user = $user_result->fetch_assoc();

// Handle review deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_review') {
    $review_id = $_POST['review_id'];
    $delete_review = $conn->prepare("DELETE FROM reviews WHERE id = ? AND user_id = ?");
    $delete_review->bind_param("ii", $review_id, $user_id);
    if ($delete_review->execute()) {
        $_SESSION['success_message'] = "Review deleted successfully!";
    }
    $delete_review->close();

    // Redirect to prevent form resubmission
    header("Location: profile.php");
    exit();
}

// Pagination for bookings
$bookings_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $bookings_per_page;

// Get total bookings count
$total_bookings_query = $conn->prepare("SELECT COUNT(*) as total FROM bookings WHERE user_id = ?");
$total_bookings_query->bind_param("i", $user_id);
$total_bookings_query->execute();
$total_bookings_result = $total_bookings_query->get_result();
$total_bookings = $total_bookings_result->fetch_assoc()['total'];
$total_pages = ceil($total_bookings / $bookings_per_page);

// Get user bookings with pagination
$bookings_query = $conn->prepare("SELECT * FROM bookings WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
$bookings_query->bind_param("iii", $user_id, $bookings_per_page, $offset);
$bookings_query->execute();
$bookings_result = $bookings_query->get_result();

$bookings_arr = [];
while ($booking = $bookings_result->fetch_assoc()) {
    $bookings_arr[] = $booking;
}

// Get user reviews
$reviews_query = $conn->prepare("
    SELECT r.*, b.destination, b.trip_type
    FROM reviews r
    JOIN bookings b ON r.booking_id = b.id
    WHERE r.user_id = ?
    ORDER BY r.created_at DESC
");
$reviews_query->bind_param("i", $user_id);
$reviews_query->execute();
$reviews_result = $reviews_query->get_result();

$reviews_arr = [];
while ($review = $reviews_result->fetch_assoc()) {
    $reviews_arr[] = $review;
}

// Get user activities
$activities_query = $conn->prepare("SELECT * FROM bookings WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$activities_query->bind_param("i", $user_id);
$activities_query->execute();
$activities_result = $activities_query->get_result();

$activities = [];
while ($activity = $activities_result->fetch_assoc()) {
    $activities[] = $activity;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Know UP</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 50%, #16213e 100%);
            min-height: 100vh;
            color: #3f3f3fff;
            line-height: 1.6;
        }

        .page-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .page-header {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            font-weight: bold;
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.4);
        }

        .user-details h1 {
            font-size: 28px;
            color: #f1f5f9;
            margin-bottom: 5px;
        }

        .user-details p {
            color: #94a3b8;
            font-size: 16px;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .btn-secondary {
            background: rgba(30, 41, 59, 0.8);
            color: #cbd5e1;
            border: 2px solid rgba(59, 130, 246, 0.3);
        }

        .btn-secondary:hover {
            background: rgba(30, 41, 59, 1);
            border-color: rgba(59, 130, 246, 0.5);
        }

        .btn-danger {
            background: linear-gradient(135deg, #fc8181, #e53e3e);
            color: white;
        }

        .success-alert {
            background: linear-gradient(135deg, #065f46, #059669);
            color: white;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-weight: 500;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .main-grid {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 30px;
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .stats-card {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
            background: rgba(30, 41, 59, 0.5);
            border-radius: 30px;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }
        .stat-item:hover{
            background: rgba(30, 41, 59, 0.7);
            border-color: rgba(59, 130, 246, 0.4);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2);
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #60a5fa;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 12px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .activity-card {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .quick-action-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 16px 20px;
            margin-bottom: 12px;
            background: rgba(30, 41, 59, 0.4);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .quick-action-item:hover {
            background: rgba(30, 41, 59, 0.7);
            border-color: rgba(59, 130, 246, 0.4);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2);
        }

        .quick-action-item:last-child {
            margin-bottom: 0;
        }

        .quick-action-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .main-content {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .content-card {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .search-box {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid rgba(59, 130, 246, 0.3);
            border-radius: 12px;
            font-size: 14px;
            margin-bottom: 20px;
            transition: border-color 0.3s ease;
            background: rgba(30, 41, 59, 0.5);
            color: #e2e8f0;
        }

        .search-box:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .filter-tab {
            background: rgba(30, 41, 59, 0.5);
            border: 2px solid rgba(59, 130, 246, 0.3);
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: #cbd5e1;
            transition: all 0.3s ease;
        }

        .filter-tab.active {
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            color: white;
            border-color: transparent;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .data-table th,
        .data-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(59, 130, 246, 0.2);
            color: #e2e8f0;
        }

        .data-table th {
            background: rgba(30, 41, 59, 0.8);
            font-weight: 600;
            color: #f1f5f9;
            font-size: 14px;
        }

        .data-table tbody tr:hover {
            background: rgba(59, 130, 246, 0.1);
        }

        .status-badge {
            padding: 6px 0;
            border-radius: 11px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-completed {
            background: #c6f6d5;
            color: #22543d;
        }

        .status-pending {
            background: #feebc8;
            color: #c05621;
        }

        .btn-small {
            padding: 8px 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            margin: 2px;
            transition: all 0.3s ease;
        }

        .btn-view {
            background: #3b82f6;
            color: white;
        }

        .btn-review {
            background: #48bb78;
            color: white;
        }

        .btn-delete {
            background: #e53e3e;
            color: white;
        }

        .review-card {
            background: rgba(30, 41, 59, 0.5);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .review-destination {
            font-weight: bold;
            color: #60a5fa;
            font-size: 16px;
        }

        .stars {
            color: #f6ad55;
            font-size: 18px;
        }

        .review-text {
            color: #cbd5e1;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .review-date {
            font-size: 12px;
            color: #718096;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 30px;
        }

        .page-btn {
            padding: 10px 15px;
            border: 2px solid rgba(59, 130, 246, 0.3);
            background: rgba(30, 41, 59, 0.5);
            color: #cbd5e1;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .page-btn:hover {
            background: rgba(30, 41, 59, 0.8);
            border-color: rgba(59, 130, 246, 0.5);
        }

        .page-btn.active {
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            color: white;
            border-color: transparent;
        }



        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #f1f5f9;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid rgba(59, 130, 246, 0.3);
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            background: rgba(30, 41, 59, 0.5);
            color: #e2e8f0;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .star-rating {
            display: flex;
            gap: 5px;
            margin: 10px 0;
        }

        .star {
            font-size: 24px;
            color: #e2e8f0;
            cursor: pointer;
            transition: color 0.2s;
        }

        .star.active,
        .star:hover {
            color: #f6ad55;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
            margin-top: 20px;

        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 14px;
            font-weight: 500;
            color: #e2e8f0;
            background: rgba(30, 41, 59, 0.3);
        }

        .calendar-day:hover {
            background: rgba(59, 130, 246, 0.4);
            color: white;
        }

        .calendar-day.today {
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            color: white;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .calendar-day.has-booking {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .calendar-header {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
            margin-bottom: 10px;
        }

        .calendar-header-day {
            text-align: center;
            font-weight: bold;
            color: #f1f5f9;
            padding: 10px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        @media (max-width: 1024px) {
            .main-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .payment-option:hover {
            border-color: #3b82f6;
            background: rgba(59, 130, 246, 0.05);
        }

        .payment-option.selected {
            border-color: #3b82f6;
            background: rgba(59, 130, 246, 0.1);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2);
        }

        .payment-processing {
            text-align: center;
            padding: 30px;
        }

        .payment-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(59, 130, 246, 0.3);
            border-top: 4px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .page-container {
                padding: 15px;
            }

            .header-content {
                flex-direction: column;
                text-align: center;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .data-table {
                font-size: 12px;
            }

            .data-table th,
            .data-table td {
                padding: 10px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <!-- Header Section -->
        <div class="page-header">
            <div class="header-content">
                <div class="user-info">
                    <div class="user-avatar"><?= htmlspecialchars(strtoupper(substr($user['name'], 0, 1))) ?></div>
                    <div class="user-details">
                        <h1><?= htmlspecialchars($user['name']) ?></h1>
                        <p><?= htmlspecialchars($user['email']) ?></p>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="logout.php" class="btn btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>

        <?php if (isset($_SESSION['success_message'])): ?>
        <div class="success-alert">
            <i class="fas fa-check-circle"></i> <?= $_SESSION['success_message'] ?>
        </div>
        <?php 
            unset($_SESSION['success_message']); // Clear the message after displaying
        endif; 
        ?>

        <!-- Main Grid Layout -->
        <div class="main-grid">
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- User Stats -->
                <div class="stats-card">
                    <h3 class="section-title">
                        <i class="fas fa-chart-bar"></i> Your Stats
                    </h3>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-number"><?= count($bookings_arr) ?></div>
                            <div class="stat-label">Total Trips</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?= count(array_filter($bookings_arr, function($b) { return $b['payment_status'] === 'completed'; })) ?></div>
                            <div class="stat-label">Completed</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?= count($reviews_arr) ?></div>
                            <div class="stat-label">Reviews</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?= count($reviews_arr) > 0 ? number_format(array_sum(array_column($reviews_arr, 'rating')) / count($reviews_arr), 1) : 'N/A' ?></div>
                            <div class="stat-label">Avg Rating</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="activity-card">
                    <h3 class="section-title">
                        <i class="fas fa-compass"></i> Quick Actions
                    </h3>
                    <div id="quickActionsContainer">
                        <div class="quick-action-item" onclick="navigateTo('../index.html#cities')">
                            <div class="quick-action-icon" style="background: linear-gradient(135deg, #059669, #10b981);">
                                <i class="fas fa-city"></i>
                            </div>
                            <div>
                                <div style="font-weight: 500; color: #f1f5f9;">Explore Cities</div>
                                <div style="font-size: 12px; color: #94a3b8;">Discover amazing destinations</div>
                            </div>
                            <i class="fas fa-chevron-right" style="color: #64748b; margin-left: auto;"></i>
                        </div>

                        <div class="quick-action-item" onclick="navigateTo('../index.html#packages')">
                            <div class="quick-action-icon" style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                                <i class="fas fa-box"></i>
                            </div>
                            <div>
                                <div style="font-weight: 500; color: #f1f5f9;">Tour Packages</div>
                                <div style="font-size: 12px; color: #94a3b8;">Pre-planned tour packages</div>
                            </div>
                            <i class="fas fa-chevron-right" style="color: #64748b; margin-left: auto;"></i>
                        </div>

                        <div class="quick-action-item" onclick="navigateTo('../ExploreUP/exploreUP.html')">
                            <div class="quick-action-icon" style="background: linear-gradient(135deg, #dc2626, #ef4444);">
                                <i class="fas fa-compass"></i>
                            </div>
                            <div>
                                <div style="font-weight: 500; color: #f1f5f9;">Explore UP</div>
                                <div style="font-size: 12px; color: #94a3b8;">Detailed UP exploration</div>
                            </div>
                            <i class="fas fa-chevron-right" style="color: #64748b; margin-left: auto;"></i>
                        </div>

                        <div class="quick-action-item" onclick="navigateTo('../reviews.html')">
                            <div class="quick-action-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                                <i class="fas fa-star"></i>
                            </div>
                            <div>
                                <div style="font-weight: 500; color: #f1f5f9;">Reviews</div>
                                <div style="font-size: 12px; color: #94a3b8;">Read customer reviews</div>
                            </div>
                            <i class="fas fa-chevron-right" style="color: #64748b; margin-left: auto;"></i>
                        </div>

                        <div class="quick-action-item" onclick="navigateTo('../ExploreUP/event/festival-calendar.html')">
                            <div class="quick-action-icon" style="background: linear-gradient(135deg, #0ea5e9, #06b6d4);">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <div style="font-weight: 500; color: #f1f5f9;">Festival Calendar</div>
                                <div style="font-size: 12px; color: #94a3b8;">UP festivals & events</div>
                            </div>
                            <i class="fas fa-chevron-right" style="color: #64748b; margin-left: auto;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <!-- Bookings Section -->
                <div class="content-card">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="fas fa-suitcase"></i> Your Bookings
                        </h3>
                        <span style="color: #718096; font-size: 14px;">Page <?= $page ?> of <?= $total_pages ?> (<?= $total_bookings ?> total)</span>
                    </div>

                    <input type="text" class="search-box" placeholder="Search bookings..." id="bookingSearch" onkeyup="filterBookings()">

                    <div class="filter-tabs">
                        <div class="filter-tab active" onclick="filterByStatus('all')">All</div>
                        <div class="filter-tab" onclick="filterByStatus('completed')">Completed</div>
                        <div class="filter-tab" onclick="filterByStatus('pending')">Pending</div>
                    </div>

                    <table class="data-table" id="bookingsTable">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Destination</th>
                                <th>Dates</th>
                                <th>Guests</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($bookings_arr)): ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 40px; color: #718096;">
                                        <div><i class="fas fa-suitcase" style="font-size: 48px; margin-bottom: 15px;"></i></div>
                                        <div style="font-size: 18px; margin-bottom: 8px;">No bookings found</div>
                                        <div style="font-size: 14px;">Start planning your next adventure!</div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($bookings_arr as $index => $booking): ?>
                                    <tr class="booking-row" data-status="<?= $booking['payment_status'] ?>">
                                        <td><?= ($page - 1) * $bookings_per_page + $index + 1 ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($booking['destination']) ?></strong><br>
                                            <small style="color: #718096;"><?= htmlspecialchars($booking['trip_type']) ?></small>
                                        </td>
                                        <td>
                                            <div><?= date('M d, Y', strtotime($booking['check_in'])) ?></div>
                                            <small style="color: #718096;">to <?= date('M d, Y', strtotime($booking['check_out'])) ?></small>
                                        </td>
                                        <td><?= $booking['adults'] ?> Adults<br><small style="color: #718096;"><?= $booking['children'] ?> Children</small></td>
                                        <td>
                                            <?php 
                                                // payment_amount already includes GST, so we need to extract the base and GST
                                                $totalAmount = floatval($booking['payment_amount']);
                                                $baseAmount = $totalAmount / 1.18; // Extract base amount (total / 1.18)
                                                $gstAmount = $totalAmount - $baseAmount; // GST is the difference
                                            ?>
                                            <strong>₹<?= number_format($totalAmount, 2) ?></strong>
                                            <br><small style="color: #94a3b8;">Base: ₹<?= number_format($baseAmount, 2) ?></small>
                                            <br><small style="color: #94a3b8;">GST (18%): ₹<?= number_format($gstAmount, 2) ?></small>
                                        </td>
                                        <td>
                                            <span class="status-badge status-<?= $booking['payment_status'] ?>">
                                                <?= ucfirst($booking['payment_status']) ?>
                                                <?php if ($booking['payment_status'] === 'pending'): ?>
                                                    <small style="display: block; font-size: 10px; margin-top: 2px; font-weight: normal;">Payment Required</small>
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn-small btn-view" onclick="showBookingDetails(<?= $booking['id'] ?>)">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            <?php if ($booking['payment_status'] === 'pending'): ?>
                                                <button class="btn-small btn-review" onclick="payNow(<?= $booking['id'] ?>)" style="background: #f59e0b;">
                                                    <i class="fas fa-credit-card"></i> Pay Now
                                                </button>
                                            <?php endif; ?>
                                            <button class="btn-small btn-delete" onclick="deleteBooking(<?= $booking['id'] ?>)">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                            <?php if ($booking['payment_status'] === 'completed'): ?>
                                                <button class="btn-small btn-review" onclick="showReviewModal(<?= $booking['id'] ?>, '<?= htmlspecialchars($booking['destination']) ?>')">
                                                    <i class="fas fa-star"></i> Review
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=1" class="page-btn">« First</a>
                            <a href="?page=<?= $page - 1 ?>" class="page-btn">‹ Previous</a>
                        <?php endif; ?>

                        <?php
                        $start = max(1, $page - 2);
                        $end = min($total_pages, $page + 2);
                        for ($i = $start; $i <= $end; $i++):
                        ?>
                            <a href="?page=<?= $i ?>" class="page-btn <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?= $page + 1 ?>" class="page-btn">Next ›</a>
                            <a href="?page=<?= $total_pages ?>" class="page-btn">Last »</a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Reviews Section -->
                <div class="content-card">
                    <h3 class="section-title">
                        <i class="fas fa-star"></i> My Reviews
                    </h3>

                    <?php if (empty($reviews_arr)): ?>
                        <div style="text-align: center; padding: 40px; color: #718096;">
                            <div><i class="fas fa-star" style="font-size: 48px; margin-bottom: 15px;"></i></div>
                            <div style="font-size: 18px; margin-bottom: 8px;">No reviews yet</div>
                            <div style="font-size: 14px;">Complete a trip to leave your first review!</div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($reviews_arr as $review): ?>
                            <div class="review-card">
                                <div class="review-header">
                                    <div class="review-destination"><?= htmlspecialchars($review['destination']) ?></div>
                                    <div class="stars">
                                        <?= str_repeat('★', $review['rating']) ?>
                                    </div>
                                </div>
                                <div class="review-text"><?= htmlspecialchars($review['review_text']) ?></div>
                                <div class="review-date">Reviewed on <?= date('M d, Y', strtotime($review['created_at'])) ?></div>
                                <div style="margin-top: 15px;">
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this review?')">
                                        <input type="hidden" name="action" value="delete_review">
                                        <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                        <button type="submit" class="btn-small btn-delete">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>



    <script>
        // User data from PHP
        const userName = <?= json_encode($user['name']) ?>;
        const userEmail = <?= json_encode($user['email']) ?>;
        const bookings = <?= json_encode($bookings_arr) ?>;

        let currentCalendarDate = new Date();
        let selectedRating = 0;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            setupStarRating();
        });

        // Star rating functionality
        function setupStarRating() {
            const stars = document.querySelectorAll('.star');
            stars.forEach((star, index) => {
                star.addEventListener('click', () => {
                    selectedRating = index + 1;
                    document.getElementById('ratingValue').value = selectedRating;
                    updateStarDisplay();
                });

                star.addEventListener('mouseover', () => {
                    updateStarDisplay(index + 1);
                });
            });

            document.getElementById('starRating').addEventListener('mouseleave', () => {
                updateStarDisplay();
            });
        }

        function updateStarDisplay(hoverRating = null) {
            const stars = document.querySelectorAll('.star');
            const displayRating = hoverRating || selectedRating;

            stars.forEach((star, index) => {
                if (index < displayRating) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
        }

        // Filter bookings by status
        function filterByStatus(status) {
            const tabs = document.querySelectorAll('.filter-tab');
            tabs.forEach(tab => tab.classList.remove('active'));
            event.target.classList.add('active');

            const rows = document.querySelectorAll('.booking-row');
            rows.forEach(row => {
                if (status === 'all' || row.dataset.status === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Search bookings
        function filterBookings() {
            const input = document.getElementById('bookingSearch');
            const filter = input.value.toLowerCase();
            const rows = document.querySelectorAll('.booking-row');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function showBookingDetails(bookingId) {
            const booking = bookings.find(b => b.id == bookingId);
            if (!booking) {
                alert('Booking not found!');
                return;
            }

            // Create modal if it doesn't exist
            let modal = document.getElementById('detailsModal');
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'detailsModal';
                modal.className = 'modal';
                modal.style.cssText = `
                    display: block;
                    position: fixed;
                    z-index: 1000;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0,0,0,0.8);
                    overflow: auto;
                `;

                const modalContent = document.createElement('div');
                modalContent.id = 'detailsContent';
                modalContent.style.cssText = `
                    background-color: #fefefe;
                    margin: 2% auto;
                    padding: 0;
                    border-radius: 15px;
                    width: 90%;
                    max-width: 600px;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
                    position: relative;
                `;

                modal.appendChild(modalContent);
                document.body.appendChild(modal);

                // Close modal when clicking outside
                modal.onclick = function(event) {
                    if (event.target === modal) {
                        modal.style.display = 'none';
                    }
                };
            }

            const content = document.getElementById('detailsContent');
            const checkInDate = new Date(booking.check_in);
            const checkOutDate = new Date(booking.check_out);
            const days = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));

            content.innerHTML = `
                <div style="background: rgba(0, 0, 0, 0.95); border-radius: 20px; overflow: hidden; color: #e2e8f0;">
                    <div style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; padding: 25px; text-align: center; position: relative;">
                        <button onclick="document.getElementById('detailsModal').style.display='none'" style="position: absolute; top: 15px; right: 20px; background: none; border: none; color: white; font-size: 24px; cursor: pointer; padding: 5px;">&times;</button>
                        <h2 style="margin: 0; font-size: 24px;">Booking Details</h2>
                        <p style="margin: 8px 0 0 0; opacity: 0.9;">Know UP Tourism</p>
                        <p style="margin: 8px 0 0 0; font-size: 14px;">Booking ID: #${booking.id}</p>
                    </div>

                    <div style="padding: 25px;">
                        <div style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #4a5568;">
                            <h4 style="margin: 0 0 10px 0; color: #f1f5f9;">Trip Information</h4>
                            <p><strong>Destination:</strong> ${booking.destination}</p>
                            <p><strong>Trip Type:</strong> ${booking.trip_type.replace('_', ' ').toUpperCase()}</p>
                            <p><strong>Check-in:</strong> ${checkInDate.toLocaleDateString()}</p>
                            <p><strong>Check-out:</strong> ${checkOutDate.toLocaleDateString()}</p>
                            <p><strong>Duration:</strong> ${days} Day${days > 1 ? 's' : ''}</p>
                            <p><strong>Guests:</strong> ${booking.adults} Adults, ${booking.children} Children</p>
                        </div>

                        <div style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #4a5568;">
                            <h4 style="margin: 0 0 10px 0; color: #f1f5f9;">Payment Details</h4>
                            <p><strong>Base Amount:</strong> ₹${(Number(booking.payment_amount) / 1.18).toLocaleString()}</p>
                            <p><strong>GST (18%):</strong> ₹${(Number(booking.payment_amount) - (Number(booking.payment_amount) / 1.18)).toLocaleString()}</p>
                            <p><strong>Total Amount:</strong> ₹${Number(booking.payment_amount).toLocaleString()}</p>
                            <p><strong>Status:</strong> 
                                <span style="padding: 4px 12px; border-radius: 15px; font-size: 12px; font-weight: bold; ${
                                    booking.payment_status === 'completed' ? 'background: #22543d; color: #c6f6d5;' :
                                    booking.payment_status === 'pending' ? 'background: #c05621; color: #feebc8;' :
                                    'background: #dc2626; color: #fee2e2;'
                                }">
                                    ${booking.payment_status.toUpperCase()}
                                </span>
                            </p>
                        </div>

                        <div style="text-align: center;">
                            <button onclick="downloadReceipt(${booking.id})" style="background: #3b82f6; color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; margin-right: 10px;">
                                <i class="fas fa-download"></i> Download Receipt
                            </button>
                            <button onclick="document.getElementById('detailsModal').style.display='none'" style="background: #6b7280; color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer;">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            `;

            modal.style.display = 'block';
        }

        function showReviewModal(bookingId, destination) {
            // Create modal if it doesn't exist
            let modal = document.getElementById('reviewModal');
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'reviewModal';
                modal.className = 'modal';
                modal.style.cssText = `
                    display: block;
                    position: fixed;
                    z-index: 1000;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0,0,0,0.8);
                    overflow: auto;
                `;

                const modalContent = document.createElement('div');
                modalContent.style.cssText = `
                    background-color: #fefefe;
                    margin: 5% auto;
                    padding: 0;
                    border-radius: 15px;
                    width: 90%;
                    max-width: 500px;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
                `;

                modal.appendChild(modalContent);
                document.body.appendChild(modal);

                // Close modal when clicking outside
                modal.onclick = function(event) {
                    if (event.target === modal) {
                        modal.style.display = 'none';
                    }
                };
            }

            const content = modal.querySelector('div');
            content.innerHTML = `
                <div style="background: rgba(0, 0, 0, 0.95); border-radius: 20px; overflow: hidden; color: #e2e8f0;">
                    <div style="background: linear-gradient(135deg, #48bb78, #38a169); color: white; padding: 25px; text-align: center; position: relative;">
                        <button onclick="document.getElementById('reviewModal').style.display='none'" style="position: absolute; top: 15px; right: 20px; background: none; border: none; color: white; font-size: 24px; cursor: pointer; padding: 5px;">&times;</button>
                        <h2 style="margin: 0; font-size: 24px;">Write a Review</h2>
                        <p style="margin: 8px 0 0 0; opacity: 0.9;">${destination}</p>
                    </div>

                    <form onsubmit="submitReview(event, ${bookingId})" style="padding: 25px;">
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 10px; font-weight: bold; color: #f1f5f9;">Rating:</label>
                            <div id="starRating" style="display: flex; gap: 5px; margin-bottom: 15px;">
                                <span class="star" onclick="setRating(1)" style="font-size: 30px; color: #e2e8f0; cursor: pointer;">★</span>
                                <span class="star" onclick="setRating(2)" style="font-size: 30px; color: #e2e8f0; cursor: pointer;">★</span>
                                <span class="star" onclick="setRating(3)" style="font-size: 30px; color: #e2e8f0; cursor: pointer;">★</span>
                                <span class="star" onclick="setRating(4)" style="font-size: 30px; color: #e2e8f0; cursor: pointer;">★</span>
                                <span class="star" onclick="setRating(5)" style="font-size: 30px; color: #e2e8f0; cursor: pointer;">★</span>
                            </div>
                            <input type="hidden" id="rating" value="0">
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 10px; font-weight: bold; color: #f1f5f9;">Your Review:</label>
                            <textarea id="reviewText" rows="4" placeholder="Share your experience..." style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px; resize: vertical;"></textarea>
                        </div>

                        <div style="text-align: center;">
                            <button type="submit" style="background: #48bb78; color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; margin-right: 10px; font-weight: bold;">
                                <i class="fas fa-star"></i> Submit Review
                            </button>
                            <button type="button" onclick="document.getElementById('reviewModal').style.display='none'" style="background: #6b7280; color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer;">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            `;

            selectedRating = 0;
            modal.style.display = 'block';
        }

        function setRating(rating) {
            selectedRating = rating;
            document.getElementById('rating').value = rating;

            const stars = document.querySelectorAll('.star');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.style.color = '#f6ad55';
                } else {
                    star.style.color = '#e2e8f0';
                }
            });
        }

        function submitReview(event, bookingId) {
            event.preventDefault();

            const rating = selectedRating;
            const reviewText = document.getElementById('reviewText').value;

            if (!rating || rating === 0) {
                alert('Please select a rating by clicking the stars');
                return;
            }

            const formData = new FormData();
            formData.append('booking_id', bookingId);
            formData.append('rating', rating);
            formData.append('review_text', reviewText);

            fetch('submit_review.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Review submitted successfully!');
                    document.getElementById('reviewModal').style.display = 'none';
                    if (data.redirect) {
                        window.location.href = 'profile.php';
                    } else {
                        setTimeout(() => location.reload(), 500);
                    }
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while submitting the review');
            });
        }

        // Delete booking function
        function deleteBooking(bookingId) {
            if (confirm('Are you sure you want to delete this booking? This action cannot be undone.')) {
                const formData = new FormData();
                formData.append('booking_id', bookingId);
                formData.append('action', 'cancel');

                fetch('cancel_booking.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Booking deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Unable to delete booking'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the booking');
                });
            }
        }

        // Download receipt function
        function downloadReceipt(bookingId) {
            const booking = bookings.find(b => b.id == bookingId);
            if (!booking) return;

            // Create a new window for the receipt
            const receiptWindow = window.open('');

            const checkInDate = new Date(booking.check_in);
            const checkOutDate = new Date(booking.check_out);
            const days = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
            const totalAmount = parseFloat(booking.payment_amount);
            const baseAmount = totalAmount / 1.18; // Extract base amount
            const taxAmount = totalAmount - baseAmount; // GST amount

            receiptWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Booking Receipt #${booking.id}</title>
                    <style>
                        * { margin: 0; padding: 0; box-sizing: border-box; }
                        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
                        .receipt { max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
                        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; }
                        .content { padding: 30px; }
                        .section { margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #eee; }
                        .section:last-child { border-bottom: none; }
                        .section h3 { margin-bottom: 15px; color: #333; font-size: 18px; }
                        .row { display: flex; justify-content: space-between; margin-bottom: 8px; }
                        .total-row { font-weight: bold; font-size: 18px; border-top: 2px solid #667eea; padding-top: 10px; margin-top: 15px; }
                        .status { display: inline-block; padding: 6px 15px; border-radius: 20px; font-weight: bold; text-transform: uppercase; font-size: 12px; }
                        .status.completed { background: #d4edda; color: #155724; }
                        .status.pending { background: #fff3cd; color: #856404; }
                        .print-btn { background: #667eea; color: white; border: none; padding: 12px 24px; border-radius: 5px; cursor: pointer; margin: 20px 0; font-size: 16px; }
                        @media print { .print-btn { display: none; } }
                    </style>
                </head>
                <body>
                    <div class="receipt">
                        <div class="header">
                            <h1>BOOKING RECEIPT</h1>
                            <p>Know UP Tourism</p>
                            <p>Receipt #${booking.id} | ${new Date(booking.created_at).toLocaleDateString()}</p>
                        </div>

                        <div class="content">
                            <button class="print-btn" onclick="window.print()">🖨️ Print Receipt</button>

                            <div class="section">
                                <h3>Customer Details</h3>
                                <div class="row"><span>Name:</span><span>${userName}</span></div>
                                <div class="row"><span>Email:</span><span>${userEmail}</span></div>
                                <div class="row"><span>Booking Date:</span><span>${new Date(booking.created_at).toLocaleDateString()}</span></div>
                            </div>

                            <div class="section">
                                <h3>Trip Information</h3>
                                <div class="row"><span>Destination:</span><span>${booking.destination}</span></div>
                                <div class="row"><span>Trip Type:</span><span>${booking.trip_type.replace('_', ' ').toUpperCase()}</span></div>
                                <div class="row"><span>Check-in:</span><span>${checkInDate.toLocaleDateString('en-IN', {weekday: 'short', year: 'numeric', month: 'short', day: 'numeric'})}</span></div>
                                <div class="row"><span>Check-out:</span><span>${checkOutDate.toLocaleDateString('en-IN', {weekday: 'short', year: 'numeric', month: 'short', day: 'numeric'})}</span></div>
                                <div class="row"><span>Duration:</span><span>${days} Day${days > 1 ? 's' : ''}</span></div>
                                <div class="row"><span>Guests:</span><span>${booking.adults} Adult${booking.adults > 1 ? 's' : ''}, ${booking.children} Child${booking.children != 1 ? 'ren' : ''}</span></div>
                                ${booking.special_requests ? `<div class="row"><span>Special Requests:</span><span>${booking.special_requests}</span></div>` : ''}
                            </div>

                            <div class="section">
                                <h3>Billing Details</h3>
                                <div class="row"><span>Trip Cost (${days} day${days > 1 ? 's' : ''} × ${booking.adults} adult${booking.adults > 1 ? 's' : ''}${booking.children > 0 ? ' + ' + booking.children + ' child' + (booking.children > 1 ? 'ren' : '') : ''}):</span><span>₹${baseAmount.toFixed(2)}</span></div>
                                <div class="row"><span>Taxes & Fees (18% GST):</span><span>₹${taxAmount.toFixed(2)}</span></div>
                                <div class="row total-row"><span>Total Paid:</span><span>₹${totalAmount.toFixed(2)}</span></div>
                            </div>

                            <div class="section">
                                <h3>Payment Status</h3>
                                <div style="text-align: center;">
                                    <span class="status ${booking.payment_status}">${booking.payment_status.charAt(0).toUpperCase() + booking.payment_status.slice(1)}</span>
                                </div>
                                <div style="text-align: center; margin-top: 20px; font-size: 14px; color: #666;">
                                    <p>Thank you for choosing Know UP Tourism!</p>
                                    <p>For support: Knowup65@gmail.com | 9876543211</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </body>
                </html>
            `);

            receiptWindow.document.close();
        }

        // Pay Now functionality - open payment page and handle completion
        function payNow(bookingId) {
            const booking = bookings.find(b => b.id == bookingId);
            if (!booking) return;

            // Prepare booking data for payment page
            const paymentData = {
                bookingType: 'existing',
                bookingId: booking.id,
                destination: booking.destination,
                checkIn: booking.check_in,
                checkOut: booking.check_out,
                adults: booking.adults,
                children: booking.children,
                tripType: booking.trip_type,
                specialRequests: booking.special_requests,
                payment_amount: booking.payment_amount,
                totalAmount: `₹${Number(booking.payment_amount).toLocaleString()}`
            };

            // Store data in sessionStorage for payment page
            sessionStorage.setItem('bookingData', JSON.stringify(paymentData));

            // Open payment page in new window
            const paymentWindow = window.open('../payment.html');

            // Listen for payment completion message
            const messageHandler = function(event) {
                // Ensure the message is from our origin and contains payment success data
                if (event.origin !== window.location.origin) return;
                if (event.data && event.data.type === 'paymentSuccess' && event.data.bookingId === bookingId) {
                    // Payment completed successfully
                    alert('Payment completed successfully! Refreshing your bookings...');
                    window.removeEventListener('message', messageHandler);
                    location.reload(); // Refresh the page to show updated status
                } else if (event.data && event.data.type === 'paymentError' && event.data.bookingId === bookingId) {
                    // Payment failed
                    alert('Payment failed: ' + event.data.message);
                    window.removeEventListener('message', messageHandler);
                }
            };

            window.addEventListener('message', messageHandler);

            // Clean up if payment window is closed without completion
            const checkClosed = setInterval(() => {
                if (paymentWindow.closed) {
                    clearInterval(checkClosed);
                    window.removeEventListener('message', messageHandler);
                }
            }, 1000);
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }

        // Navigation function for quick actions
        function navigateTo(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>