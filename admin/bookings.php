<?php
session_start();
require_once "../fiels/db.php";

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_status') {
        $booking_id = $_POST['booking_id'];
        $status = $_POST['status'];

        $stmt = $conn->prepare("UPDATE bookings SET payment_status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $booking_id);
        $stmt->execute();

        $success_message = "Booking status updated successfully!";
    }
}

$bookings_query = "
    SELECT b.*, u.name as user_name, u.email as user_email 
    FROM bookings b 
    JOIN users u ON b.user_id = u.id 
    ORDER BY b.created_at DESC
";
$bookings_result = $conn->query($bookings_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings Management - Know UP Admin</title>
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
        }

        .section-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
            background: #f8fafc;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table.compact th,
        .data-table.compact td {
            padding: 0.5rem;
            font-size: 0.8125rem;
        }

        .data-table th,
        .data-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        .data-table th {
            background: #f8fafc;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.875rem;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }

        .btn {
            padding: 0.5rem 0.75rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.75rem;
            margin: 0.125rem;
            transition: all 0.2s ease;
        }

        .btn-primary { background: var(--primary-blue); color: white; }
        .btn-success { background: #16a34a; color: white; }
        .btn-danger { background: #dc2626; color: white; }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .success-message {
            background: #d1fae5;
            color: #065f46;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }

        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 2rem;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .close {
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
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
                <a href="bookings.php" class="menu-item active">
                    <i class="fas fa-calendar-check"></i>
                    Bookings
                </a>
                <a href="users.php" class="menu-item">
                    <i class="fas fa-users"></i>
                    Users
                </a>
                <a href="reviews.php" class="menu-item">
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
                <h1>Bookings Management</h1>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>

            <?php if (isset($success_message)): ?>
            <div class="success-message"><?= $success_message ?></div>
            <?php endif; ?>

            <div class="content-section">
                <div class="search-filter">
                    <input type="text" class="search-input" placeholder="Search bookings..." id="searchInput" onkeyup="filterBookings()">
                </div>

                <div class="section-header">
                <h2>All Bookings</h2>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <label style="display: flex; align-items: center; gap: 5px; font-size: 0.875rem;">
                        <input type="checkbox" id="compactMode" onchange="toggleCompactMode()">
                        Compact Mode
                    </label>
                    <button class="btn btn-primary" onclick="exportToPDF()">
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>
                    <span>Total: <?= $bookings_result->num_rows ?> bookings</span>
                </div>
            </div>

                <div style="overflow-x: auto;">
                    <table class="data-table" id="bookingsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Destination</th>
                                <th>Dates</th>
                                <th>Guests</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($booking = $bookings_result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?= $booking['id'] ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($booking['user_name']) ?></strong><br>
                                    <small><?= htmlspecialchars($booking['user_email']) ?></small>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($booking['destination']) ?></strong><br>
                                    <small><?= htmlspecialchars($booking['trip_type']) ?></small>
                                </td>
                                <td>
                                    <?= date('M d, Y', strtotime($booking['check_in'])) ?> to<br>
                                    <?= date('M d, Y', strtotime($booking['check_out'])) ?>
                                </td>
                                <td><?= $booking['adults'] ?> Adults, <?= $booking['children'] ?> Children</td>
                                <td>₹<?= number_format($booking['payment_amount']) ?></td>
                                <td>
                                    <?php
                                    $status = $booking['payment_status'];
                                    $badgeClass = $status === 'completed' ? 'badge-success' : 
                                                 ($status === 'pending' ? 'badge-warning' : 'badge-danger');
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= ucfirst($status) ?></span>
                                </td>
                                <td>
                                    <button class="btn btn-primary" onclick="viewBookingDetails(<?= htmlspecialchars(json_encode($booking)) ?>)">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button class="btn btn-success" onclick="updateBookingStatus(<?= $booking['id'] ?>, 'completed')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-danger" onclick="updateBookingStatus(<?= $booking['id'] ?>, 'cancelled')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div id="bookingDetails"></div>
        </div>
    </div>

    <script>
        function filterBookings() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('bookingsTable');
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

        function viewBookingDetails(booking) {
            const modal = document.getElementById('bookingModal');
            const details = document.getElementById('bookingDetails');

            details.innerHTML = `
                <h2>Booking Details #${booking.id}</h2>
                <div style="margin: 20px 0;">
                    <h3>Customer Information</h3>
                    <p><strong>Name:</strong> ${booking.user_name}</p>
                    <p><strong>Email:</strong> ${booking.user_email}</p>
                </div>
                <div style="margin: 20px 0;">
                    <h3>Trip Information</h3>
                    <p><strong>Destination:</strong> ${booking.destination}</p>
                    <p><strong>Trip Type:</strong> ${booking.trip_type}</p>
                    <p><strong>Check-in:</strong> ${booking.check_in}</p>
                    <p><strong>Check-out:</strong> ${booking.check_out}</p>
                    <p><strong>Guests:</strong> ${booking.adults} Adults, ${booking.children} Children</p>
                    <p><strong>Special Requests:</strong> ${booking.special_requests || 'None'}</p>
                </div>
                <div style="margin: 20px 0;">
                    <h3>Payment Information</h3>
                    <p><strong>Amount:</strong> ₹${booking.payment_amount}</p>
                    <p><strong>Status:</strong> ${booking.payment_status}</p>
                    <p><strong>Booked On:</strong> ${booking.created_at}</p>
                </div>
            `;

            modal.style.display = 'block';
        }

        function updateBookingStatus(bookingId, status) {
            if (confirm(`Are you sure you want to mark this booking as ${status}?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="update_status">
                    <input type="hidden" name="booking_id" value="${bookingId}">
                    <input type="hidden" name="status" value="${status}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function closeModal() {
            document.getElementById('bookingModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('bookingModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }

        function toggleCompactMode() {
            const table = document.getElementById('bookingsTable');
            table.classList.toggle('compact');
        }

        

        function exportToPDF() {
            window.print();
        }
    </script>
</body>
</html>