```php
<?php

include('../../includes/config.php');
include('./auth.php');


// ===============================
// UPDATE ORDER STATUS
// ===============================

$message = "";
$error = "";

if (isset($_POST['update_status'])) {

    $orderId = intval($_POST['order_id']);
    $status = $_POST['status'];

    $allowedStatuses = [
        'pending',
        'processing',
        'shipped',
        'delivered',
        'cancelled'
    ];

    if (in_array($status, $allowedStatuses)) {

        $sql = "
            UPDATE orders
            SET status = ?
            WHERE id = ?
        ";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "si",
            $status,
            $orderId
        );

        if ($stmt->execute()) {

            $message = "Order status updated successfully.";

        } else {

            $error = "Failed to update order status.";

        }

        $stmt->close();

    } else {

        $error = "Invalid order status.";

    }
}


// ===============================
// SEARCH ORDERS
// ===============================

$search = "";

if (isset($_GET['search'])) {

    $search = trim($_GET['search']);

}


// ===============================
// GET ORDERS
// ===============================

if ($search !== "") {

    $sql = "
        SELECT
            id,
            customer_id,
            customer_name,
            total_amount,
            status,
            shipping_address,
            created_at
        FROM orders
        WHERE customer_name LIKE ?
        OR CAST(id AS CHAR) LIKE ?
        ORDER BY created_at DESC
    ";

    $stmt = $conn->prepare($sql);

    $searchTerm = "%" . $search . "%";

    $stmt->bind_param(
        "ss",
        $searchTerm,
        $searchTerm
    );

    $stmt->execute();

    $result = $stmt->get_result();

} else {

    $sql = "
        SELECT
            id,
            customer_id,
            customer_name,
            total_amount,
            status,
            shipping_address,
            created_at
        FROM orders
        ORDER BY created_at DESC
    ";

    $result = $conn->query($sql);
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Orders | Neems Fashion Cave</title>

    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/manage-orders.css">


</head>


<body>


    <!-- SIDEBAR -->

    <aside class="sidebar">

        <div class="logo">

            <h2>NEEMS</h2>

            <p>Fashion Cave</p>

        </div>


        <nav>

            <a href="index.php">
                Dashboard
            </a>

            <a href="manage-products.php">
                Products
            </a>

            <a href="add-products.php">
                Add Product
            </a>

            <a href="manage-orders.php" class="active">
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



    <!-- MAIN -->

    <main class="main">


        <!-- HEADER -->

        <div class="page-header">

            <div>

                <h1>Orders</h1>

                <p>
                    View and manage customer orders.
                </p>

            </div>

        </div>


        <!-- MESSAGES -->

        <?php if ($message): ?>

        <div class="message">

            <?php echo htmlspecialchars($message); ?>

        </div>

        <?php endif; ?>


        <?php if ($error): ?>

        <div class="error">

            <?php echo htmlspecialchars($error); ?>

        </div>

        <?php endif; ?>



        <!-- SEARCH -->

        <div class="search-box">

            <form method="GET" class="search-form">

                <input type="text" name="search" placeholder="Search by customer name or order ID..."
                    value="<?php echo htmlspecialchars($search); ?>">

                <button type="submit">
                    Search
                </button>


                <?php if ($search !== ""): ?>

                <a href="orders.php" class="clear-btn">
                    Clear
                </a>

                <?php endif; ?>

            </form>

        </div>



        <!-- ORDERS TABLE -->

        <div class="table-container">

            <table>

                <thead>

                    <tr>

                        <th>Order</th>

                        <th>Customer</th>

                        <th>Amount</th>

                        <th>Status</th>

                        <th>Delivery Address</th>

                        <th>Date</th>

                    </tr>

                </thead>


                <tbody>


                    <?php if ($result && $result->num_rows > 0): ?>


                    <?php while ($order = $result->fetch_assoc()): ?>

                    <tr>


                        <!-- ORDER ID -->

                        <td>

                            <div class="order-id">

                                #<?php echo $order['id']; ?>

                            </div>

                        </td>


                        <!-- CUSTOMER -->

                        <td>

                            <div class="customer">

                                <?php

                                echo htmlspecialchars(
                                    $order['customer_name']
                                );

                                ?>

                            </div>


                            <div class="customer-id">

                                Customer #<?php

                                echo $order['customer_id'];

                                ?>

                            </div>

                        </td>


                        <!-- AMOUNT -->

                        <td>

                            <div class="amount">

                                ₦<?php

                                echo number_format(
                                    $order['total_amount'],
                                    2
                                );

                                ?>

                            </div>

                        </td>


                        <!-- STATUS -->

                        <td>

                            <span class="status-badge <?php
                                    echo htmlspecialchars(
                                        $order['status']
                                    );
                                ?>">

                                <?php

                                echo ucfirst(
                                    htmlspecialchars(
                                        $order['status']
                                    )
                                );

                                ?>

                            </span>


                            <form method="POST" class="status-form">

                                <input type="hidden" name="order_id" value="<?php
                                        echo $order['id'];
                                    ?>">


                                <select name="status">

                                    <option value="pending" <?php
                                        echo $order['status']
                                            === 'pending'
                                            ? 'selected'
                                            : '';
                                        ?>>
                                        Pending
                                    </option>


                                    <option value="processing" <?php
                                        echo $order['status']
                                            === 'processing'
                                            ? 'selected'
                                            : '';
                                        ?>>
                                        Processing
                                    </option>


                                    <option value="shipped" <?php
                                        echo $order['status']
                                            === 'shipped'
                                            ? 'selected'
                                            : '';
                                        ?>>
                                        Shipped
                                    </option>


                                    <option value="delivered" <?php
                                        echo $order['status']
                                            === 'delivered'
                                            ? 'selected'
                                            : '';
                                        ?>>
                                        Delivered
                                    </option>


                                    <option value="cancelled" <?php
                                        echo $order['status']
                                            === 'cancelled'
                                            ? 'selected'
                                            : '';
                                        ?>>
                                        Cancelled
                                    </option>

                                </select>


                                <button type="submit" name="update_status">
                                    Update
                                </button>

                            </form>

                        </td>


                        <!-- ADDRESS -->

                        <td>

                            <div class="address">

                                <?php

                                echo htmlspecialchars(
                                    $order['shipping_address']
                                );

                                ?>

                            </div>

                        </td>


                        <!-- DATE -->

                        <td>

                            <?php

                            echo date(
                                'd M Y',
                                strtotime(
                                    $order['created_at']
                                )
                            );

                            ?>

                            <br>

                            <small>

                                <?php

                                echo date(
                                    'h:i A',
                                    strtotime(
                                        $order['created_at']
                                    )
                                );

                                ?>

                            </small>

                        </td>


                    </tr>

                    <?php endwhile; ?>


                    <?php else: ?>

                    <tr>

                        <td colspan="6" class="empty">

                            <?php if ($search !== ""): ?>

                            No orders found for
                            "<strong><?php echo htmlspecialchars($search); ?></strong>".

                            <?php else: ?>

                            No orders have been placed yet.

                            <?php endif; ?>

                        </td>

                    </tr>

                    <?php endif; ?>


                </tbody>

            </table>

        </div>


    </main>


</body>

</html>