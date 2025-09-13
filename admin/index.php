<?php
session_start();
require_once "../fiels/db.php";

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$stats_query = "
    SELECT 
        (SELECT COUNT(*) FROM users) as total_users,
        (SELECT COUNT(*) FROM bookings) as total_bookings,
        (SELECT COUNT(*) FROM bookings WHERE payment_status = 'completed') as completed_bookings,
        (SELECT COUNT(*) FROM bookings WHERE payment_status = 'pending') as pending_bookings,
        (SELECT SUM(payment_amount) FROM bookings WHERE payment_status = 'completed') as total_revenue
";
$stats_result = $conn->query($stats_query);
$stats = $stats_result->fetch_assoc();

$recent_bookings = $conn->query("
    SELECT b.*, u.name as user_name, u.email as user_email 
    FROM bookings b 
    JOIN users u ON b.user_id = u.id 
    ORDER BY b.created_at DESC 
    LIMIT 10
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Know UP - Admin Dashboard</title>
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
            line-height: 1.6;
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

        .header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
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

        .logout-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: all 0.6s ease;
        }

        .stat-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(7, 42, 217, 0.3);
        }
        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: #2600ffff;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--text-light);
            font-size: 0.875rem;
            font-weight: 500;
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
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-dark);
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

        .data-table th {
            background: #f8fafc;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.875rem;
        }

        .data-table tbody tr:hover {
            background: #f8fafc;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: var(--primary-blue);
            color: white;
        }

        .btn-primary:hover {
            background: var(--dark-blue);
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
            }

            .stats-grid {
                grid-template-columns: 1fr;
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
                <a href="index.php" class="menu-item active">
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
                <h1>Dashboard Overview</h1>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?= number_format($stats['total_users'] ?? 0) ?></div>
                    <div class="stat-label">Total Users</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= number_format($stats['total_bookings'] ?? 0) ?></div>
                    <div class="stat-label">Total Bookings</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= number_format($stats['completed_bookings'] ?? 0) ?></div>
                    <div class="stat-label">Completed Bookings</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">₹<?= number_format($stats['total_revenue'] ?? 0) ?></div>
                    <div class="stat-label">Total Revenue</div>
                </div>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Recent Bookings</h2>
                </div>
                <div style="overflow-x: auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Destination</th>
                                <th>Check-in</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($booking = $recent_bookings->fetch_assoc()): ?>
                            <tr>
                                <td>#<?= $booking['id'] ?></td>
                                <td>
                                    <div>
                                        <strong><?= htmlspecialchars($booking['user_name']) ?></strong><br>
                                        <small style="color: var(--text-light);"><?= htmlspecialchars($booking['user_email']) ?></small>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($booking['destination']) ?></td>
                                <td><?= date('M d, Y', strtotime($booking['check_in'])) ?></td>
                                <td><strong>₹<?= number_format($booking['payment_amount']) ?></strong></td>
                                <td>
                                    <?php
                                    $status = $booking['payment_status'];
                                    $badgeClass = $status === 'completed' ? 'badge-success' : 
                                                 ($status === 'pending' ? 'badge-warning' : 'badge-danger');
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= ucfirst($status) ?></span>
                                </td>
                                <td>
                                    <a href="bookings.php?id=<?= $booking['id'] ?>" class="btn btn-primary">
                                        <i class="fas fa-eye"></i>
                                        View
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>