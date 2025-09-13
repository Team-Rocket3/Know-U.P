
<?php
session_start();
require_once "../fiels/db.php";

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Get analytics data
$analytics_query = "
    SELECT 
        (SELECT COUNT(*) FROM users) as total_users,
        (SELECT COUNT(*) FROM bookings) as total_bookings,
        (SELECT COUNT(*) FROM bookings WHERE payment_status = 'completed') as completed_bookings,
        (SELECT SUM(payment_amount) FROM bookings WHERE payment_status = 'completed') as total_revenue,
        (SELECT COUNT(*) FROM bookings WHERE DATE(created_at) = CURDATE()) as today_bookings,
        (SELECT COUNT(*) FROM users WHERE DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)) as new_users_week,
        (SELECT SUM(payment_amount) FROM bookings WHERE payment_status = 'completed' AND MONTH(created_at) = MONTH(CURDATE())) as monthly_revenue
";
$analytics_result = $conn->query($analytics_query);
$analytics = $analytics_result->fetch_assoc();

// Top destinations
$destinations_query = "
    SELECT destination, COUNT(*) as bookings_count, SUM(payment_amount) as revenue
    FROM bookings 
    WHERE payment_status = 'completed' 
    GROUP BY destination 
    ORDER BY bookings_count DESC 
    LIMIT 5
";
$destinations_result = $conn->query($destinations_query);

// Monthly booking trend
$monthly_query = "
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m') as month,
        COUNT(*) as bookings,
        SUM(payment_amount) as revenue
    FROM bookings 
    WHERE payment_status = 'completed' 
    AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY month DESC
    LIMIT 12
";
$monthly_result = $conn->query($monthly_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - Know UP Admin</title>
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow);
            text-align: center;
            border: 1px solid var(--border);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--text-light);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .content-section {
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .section-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
            background: #f8fafc;
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .main-content {
                margin-left: 0;
            }

            .charts-grid {
                grid-template-columns: 1fr;
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
                <a href="reviews.php" class="menu-item">
                    <i class="fas fa-star"></i>
                    Reviews
                </a>
                <a href="analytics.php" class="menu-item active">
                    <i class="fas fa-chart-pie"></i>
                    Analytics
                </a>
            </nav>
        </div>

        <div class="main-content">
            <div class="header">
                <h1>Business Analytics</h1>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= number_format($analytics['total_users'] ?? 0) ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= number_format($analytics['today_bookings'] ?? 0) ?></div>
                <div class="stat-label">Today's Bookings</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= number_format($analytics['new_users_week'] ?? 0) ?></div>
                <div class="stat-label">New Users (7 Days)</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">₹<?= number_format($analytics['monthly_revenue'] ?? 0) ?></div>
                <div class="stat-label">Monthly Revenue</div>
            </div>
        </div>

        <div class="charts-grid">
            <div class="content-section">
                <div class="section-header">
                    <h2>Top Destinations</h2>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Destination</th>
                            <th>Bookings</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($dest = $destinations_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($dest['destination']) ?></td>
                            <td><?= $dest['bookings_count'] ?></td>
                            <td>₹<?= number_format($dest['revenue']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h2>Monthly Performance</h2>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Bookings</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($month = $monthly_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= date('M Y', strtotime($month['month'] . '-01')) ?></td>
                            <td><?= $month['bookings'] ?></td>
                            <td>₹<?= number_format($month['revenue']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
