<?php

include('../../includes/config.php');
include('./auth.php');


// Get total products
$productQuery = "SELECT COUNT(*) AS total FROM products";
$productResult = $conn->query($productQuery);
$productData = $productResult->fetch_assoc();
$totalProducts = $productData['total'];

// Get total customers
$customerQuery = "SELECT COUNT(*) AS total FROM customers";
$customerResult = $conn->query($customerQuery);
$customerData = $customerResult->fetch_assoc();
$totalCustomers = $customerData['total'];

// Get total orders
$orderQuery = "SELECT COUNT(*) AS total FROM orders";
$orderResult = $conn->query($orderQuery);
$orderData = $orderResult->fetch_assoc();
$totalOrders = $orderData['total'];

// Get total sales
$salesQuery = "SELECT SUM(total_amount) AS total FROM orders";
$salesResult = $conn->query($salesQuery);
$salesData = $salesResult->fetch_assoc();
$totalSales = $salesData['total'] ?? 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard | Neems Fashion Cave</title>

    <link rel="stylesheet" href="../css/dashboard.css">

</head>

<body>

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <div class="logo">
            <h2>NEEMS</h2>
            <p>Fashion Cave</p>
        </div>

        <nav>

            <a href="index.php" class="active">
                Dashboard
            </a>

            <a href="manage-products.php">
                Products
            </a>

            <a href="add-products.php">
                Add Product
            </a>

            <a href="manage-orders.php">
                Orders
            </a>

            <a href="manage-customers.php">
                Customers
            </a>

        </nav>

        <div class="sidebar-bottom">

            <a href="./logout.php" onclick="return confirm('Are you sure you want to logout?');">
                Logout
            </a>

        </div>

    </aside>


    <!-- MAIN CONTENT -->

    <main class="main">

        <!-- TOP BAR -->

        <header class="topbar">

            <div>

                <h1>Dashboard</h1>

                <p>
                    Welcome back,
                    <strong>
                        <?php echo htmlspecialchars($_SESSION['admin_name']); ?>
                    </strong>
                </p>

            </div>

            <div class="admin-profile">

                <div class="avatar">
                    <?php
                    echo strtoupper(substr($_SESSION['admin_name'], 0, 1));
                    ?>
                </div>

                <span>
                    <?php echo htmlspecialchars($_SESSION['admin_name']); ?>
                </span>

            </div>

        </header>


        <!-- STATISTICS -->

        <section class="stats">

            <div class="stat-card">

                <div class="stat-info">

                    <p>Total Products</p>

                    <h2>
                        <?php echo $totalProducts; ?>
                    </h2>

                </div>

                <div class="stat-icon">
                    🛍️
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-info">

                    <p>Total Orders</p>

                    <h2>
                        <?php echo $totalOrders; ?>
                    </h2>

                </div>

                <div class="stat-icon">
                    📦
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-info">

                    <p>Total Customers</p>

                    <h2>
                        <?php echo $totalCustomers; ?>
                    </h2>

                </div>

                <div class="stat-icon">
                    👥
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-info">

                    <p>Total Sales</p>

                    <h2>
                        ₦<?php echo number_format($totalSales, 2); ?>
                    </h2>

                </div>

                <div class="stat-icon">
                    💰
                </div>

            </div>

        </section>


        <!-- QUICK ACTIONS -->

        <section class="section">

            <div class="section-header">

                <h2>Quick Actions</h2>

            </div>

            <div class="actions">

                <a href="add-products.php" class="action-card">

                    <span>➕</span>

                    <div>
                        <h3>Add Product</h3>
                        <p>Add a new fashion item</p>
                    </div>

                </a>


                <a href="products.php" class="action-card">

                    <span>🛍️</span>

                    <div>
                        <h3>Manage Products</h3>
                        <p>Edit or delete products</p>
                    </div>

                </a>


                <a href="orders.php" class="action-card">

                    <span>📦</span>

                    <div>
                        <h3>Manage Orders</h3>
                        <p>View customer orders</p>
                    </div>

                </a>


                <a href="customers.php" class="action-card">

                    <span>👥</span>

                    <div>
                        <h3>Customers</h3>
                        <p>View registered customers</p>
                    </div>

                </a>

            </div>

        </section>


        <!-- RECENT ORDERS -->

        <section class="section">

            <div class="section-header">

                <h2>Recent Orders</h2>

                <a href="orders.php">
                    View All
                </a>

            </div>

            <div class="table-container">

                <table>

                    <thead>

                        <tr>

                            <th>Order ID</th>

                            <th>Customer</th>

                            <th>Amount</th>

                            <th>Status</th>

                            <th>Date</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php

                        $recentOrders = "
                            SELECT id, customer_name, total_amount, status, created_at
                            FROM orders
                            ORDER BY created_at DESC
                            LIMIT 5
                        ";

                        $ordersResult = $conn->query($recentOrders);

                        if ($ordersResult && $ordersResult->num_rows > 0):

                            while ($order = $ordersResult->fetch_assoc()):

                        ?>

                        <tr>

                            <td>
                                #<?php echo $order['id']; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($order['customer_name']); ?>
                            </td>

                            <td>
                                ₦<?php echo number_format($order['total_amount'], 2); ?>
                            </td>

                            <td>

                                <span class="status">

                                    <?php echo htmlspecialchars($order['status']); ?>

                                </span>

                            </td>

                            <td>
                                <?php echo date('d M Y', strtotime($order['created_at'])); ?>
                            </td>

                        </tr>

                        <?php

                            endwhile;

                        else:

                        ?>

                        <tr>

                            <td colspan="5" class="empty">
                                No orders yet.
                            </td>

                        </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </section>

    </main>

</body>

</html>