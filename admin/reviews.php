<?php
session_start();
require_once "../fiels/db.php";

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Handle review deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'delete_review') {
        $review_id = $_POST['review_id'];
        
        $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->bind_param("i", $review_id);
        
        if ($stmt->execute()) {
            $success_message = "Review deleted successfully!";
        } else {
            $error_message = "Failed to delete review.";
        }
    }
}

// Get all reviews with user and booking details
$reviews_query = "
    SELECT r.*, u.name as user_name, u.email as user_email, b.destination, b.trip_type
    FROM reviews r 
    JOIN users u ON r.user_id = u.id 
    JOIN bookings b ON r.booking_id = b.id 
    ORDER BY r.created_at DESC
";
$reviews_result = $conn->query($reviews_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews Management - Know UP Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #2563eb;
            --dark-blue: #1d4ed8;
            --bg-dark: #0f172a;
            --card-bg: #ffffff;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --border: #e5e7eb;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
            color: var(--text-dark);
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: linear-gradient(135deg, var(--bg-dark), #1e293b);
            color: white;
            padding: 1.5rem 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--primary-blue), #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .menu-item:hover,
        .menu-item.active {
            background: rgba(37, 99, 235, 0.1);
            border-left-color: var(--primary-blue);
            color: white;
        }

        .menu-item i {
            margin-right: 0.75rem;
            width: 18px;
        }

        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 1.5rem;
        }

        .header {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logout-btn {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            color: white;
            border: none;
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .content-section {
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
            border: 1px solid var(--border);
        }

        
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        .data-table.compact th,
        .data-table.compact td {
            padding: 0.5rem;
            font-size: 0.75rem;
        }

        .data-table th {
            background: #f8fafc;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.875rem;
        }

        .stars {
            color: #fbbf24;
        }

        .btn {
            padding: 0.5rem 0.875rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 500;
            margin: 0.125rem;
            transition: all 0.2s ease;
        }

        .btn-primary { 
            background: var(--primary-blue); 
            color: white; 
        }

        .btn-primary:hover {
            background: var(--dark-blue);
        }

        .btn-danger { 
            background: #dc2626; 
            color: white; 
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }

        .modal-content {
            background: var(--card-bg);
            margin: 5% auto;
            padding: 1.5rem;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            box-shadow: var(--shadow);
        }

        .search-filter {
            padding: 1.5rem;
            background: #f8fafc;
            border-bottom: 1px solid var(--border);
        }

        .search-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.875rem;
            transition: border-color 0.2s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .success-message {
            background: #d1fae5;
            color: #065f46;
            padding: 0.875rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border: 1px solid #a7f3d0;
        }

        .error-message {
            background: #fee2e2;
            color: #991b1b;
            padding: 0.875rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border: 1px solid #fecaca;
        }

        .review-text {
            max-width: 300px;
            word-wrap: break-word;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
            }

            .header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Know UP Admin</h2>
                <p>Management Dashboard</p>
            </div>
            <nav>
                <a href="../index.html" class="menu-item" title="Home">
                    <i class="fas fa-home"></i>
                    Home
                </a>
                <a href="index.php" class="menu-item">
                    <i class="fas fa-chart-bar"></i>
                    Dashboard
                </a>
                <a href="bookings.php" class="menu-item">
                    <i class="fas fa-calendar-check"></i>
                    Bookings
                </a>
                <a href="users.php" class="menu-item">
                    <i class="fas fa-users"></i>
                    Users
                </a>
                <a href="reviews.php" class="menu-item active">
                    <i class="fas fa-star"></i>
                    Reviews
                </a>
                <a href="analytics.php" class="menu-item">
                    <i class="fas fa-chart-pie"></i>
                    Analytics
                </a>
            </nav>
        </div>

        <div class="main-content">
            <div class="header">
                <h1>Reviews Management</h1>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>

        <?php if (isset($success_message)): ?>
        <div class="success-message"><?= $success_message ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
        <div class="error-message"><?= $error_message ?></div>
        <?php endif; ?>

        <div class="content-section">
            <div class="search-filter">
                <input type="text" class="search-input" placeholder="Search reviews..." id="searchInput" onkeyup="filterReviews()">
            </div>
            
            <table class="data-table" id="reviewsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Destination</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($reviews_result->num_rows === 0): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: #6b7280;">
                            No reviews found
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php while ($review = $reviews_result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?= $review['id'] ?></td>
                        <td>
                            <strong><?= htmlspecialchars($review['user_name']) ?></strong><br>
                            <small><?= htmlspecialchars($review['user_email']) ?></small>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($review['destination']) ?></strong><br>
                            <small><?= htmlspecialchars($review['trip_type']) ?></small>
                        </td>
                        <td>
                            <span class="stars"><?= str_repeat('★', $review['rating']) ?></span><br>
                            <small><?= $review['rating'] ?>/5</small>
                        </td>
                        <td class="review-text">
                            <?= htmlspecialchars(substr($review['review_text'], 0, 100)) ?>
                            <?= strlen($review['review_text']) > 100 ? '...' : '' ?>
                        </td>
                        <td><?= date('M d, Y', strtotime($review['created_at'])) ?></td>
                        <td>
                            <button class="btn btn-primary" onclick="viewReviewDetails(<?= htmlspecialchars(json_encode($review)) ?>)">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="btn btn-danger" onclick="deleteReview(<?= $review['id'] ?>)">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Review Details Modal -->
    <div id="reviewModal" class="modal">
        <div class="modal-content">
            <span style="float: right; cursor: pointer; font-size: 24px;" onclick="closeModal()">&times;</span>
            <div id="reviewDetails"></div>
        </div>
    </div>

    
    <script>
        
        function filterReviews() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('reviewsTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let found = false;

                for (let j = 0; j < cells.length; j++) {
                    if (cells[j].textContent.toLowerCase().includes(filter)) {
                        found = true;
                        break;
                    }
                }

                row.style.display = found ? '' : 'none';
            }
        }

        function viewReviewDetails(review) {
            const modal = document.getElementById('reviewModal');
            const details = document.getElementById('reviewDetails');
            
            details.innerHTML = `
                <h2>Review Details #${review.id}</h2>
                <div style="margin: 20px 0;">
                    <h3>Customer Information</h3>
                    <p><strong>Name:</strong> ${review.user_name}</p>
                    <p><strong>Email:</strong> ${review.user_email}</p>
                </div>
                <div style="margin: 20px 0;">
                    <h3>Trip Information</h3>
                    <p><strong>Destination:</strong> ${review.destination}</p>
                    <p><strong>Trip Type:</strong> ${review.trip_type}</p>
                </div>
                <div style="margin: 20px 0;">
                    <h3>Review Details</h3>
                    <p><strong>Rating:</strong> <span style="color: #fbbf24;">${'★'.repeat(review.rating)}</span> (${review.rating}/5)</p>
                    <p><strong>Review:</strong></p>
                    <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; margin-top: 8px;">
                        ${review.review_text}
                    </div>
                    <p><strong>Submitted:</strong> ${new Date(review.created_at).toLocaleDateString()}</p>
                </div>
            `;
            
            modal.style.display = 'block';
        }

        function deleteReview(reviewId) {
            if (confirm('Are you sure you want to delete this review? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_review">
                    <input type="hidden" name="review_id" value="${reviewId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function closeModal() {
            document.getElementById('reviewModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('reviewModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>