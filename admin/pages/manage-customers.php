```php
<?php

include('../../includes/config.php');
include('./auth.php');


// ===============================
// SEARCH CUSTOMERS
// ===============================

$search = "";

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}


// ===============================
// GET CUSTOMERS
// ===============================

if ($search !== "") {

    $sql = "
        SELECT id, name, email, phone, address, created_at
        FROM customers
        WHERE name LIKE ?
        OR email LIKE ?
        OR phone LIKE ?
        ORDER BY created_at DESC
    ";

    $stmt = $conn->prepare($sql);

    $searchTerm = "%" . $search . "%";

    $stmt->bind_param(
        "sss",
        $searchTerm,
        $searchTerm,
        $searchTerm
    );

    $stmt->execute();

    $result = $stmt->get_result();

} else {

    $sql = "
        SELECT id, name, email, phone, address, created_at
        FROM customers
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

    <title>Customers | Neems Fashion Cave</title>

    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/manage-customer.css">


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

            <a href="manage-orders.php">
                Orders
            </a>

            <a href="manage-customers.php" class="active">
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

                <h1>Customers</h1>

                <p>
                    View and manage registered customers.
                </p>

            </div>


            <div class="customer-count">

                <?php

            $countQuery =
                "SELECT COUNT(*) AS total FROM customers";

            $countResult =
                $conn->query($countQuery);

            $countData =
                $countResult->fetch_assoc();

            echo $countData['total'];

            ?>

                Customers

            </div>

        </div>



        <!-- SEARCH -->

        <div class="search-box">

            <form method="GET" class="search-form">

                <input type="text" name="search" placeholder="Search by name, email or phone..."
                    value="<?php echo htmlspecialchars($search); ?>">

                <button type="submit">
                    Search
                </button>


                <?php if ($search !== ""): ?>

                <a href="customers.php" class="clear-btn">
                    Clear
                </a>

                <?php endif; ?>

            </form>

        </div>



        <!-- CUSTOMERS TABLE -->

        <div class="table-container">

            <table>

                <thead>

                    <tr>

                        <th>Customer</th>

                        <th>Phone</th>

                        <th>Address</th>

                        <th>Registered</th>

                        <th>Customer ID</th>

                    </tr>

                </thead>


                <tbody>


                    <?php if ($result && $result->num_rows > 0): ?>


                    <?php while ($customer = $result->fetch_assoc()): ?>

                    <tr>

                        <td>

                            <div class="customer-info">

                                <div class="customer-avatar">

                                    <?php

                                    echo strtoupper(
                                        substr(
                                            $customer['name'],
                                            0,
                                            1
                                        )
                                    );

                                    ?>

                                </div>


                                <div>

                                    <div class="customer-name">

                                        <?php

                                        echo htmlspecialchars(
                                            $customer['name']
                                        );

                                        ?>

                                    </div>


                                    <div class="customer-email">

                                        <?php

                                        echo htmlspecialchars(
                                            $customer['email']
                                        );

                                        ?>

                                    </div>

                                </div>

                            </div>

                        </td>


                        <td>

                            <?php

                            echo !empty($customer['phone'])
                                ? htmlspecialchars($customer['phone'])
                                : "Not provided";

                            ?>

                        </td>


                        <td>

                            <?php

                            echo !empty($customer['address'])
                                ? htmlspecialchars($customer['address'])
                                : "Not provided";

                            ?>

                        </td>


                        <td>

                            <?php

                            echo date(
                                'd M Y',
                                strtotime(
                                    $customer['created_at']
                                )
                            );

                            ?>

                        </td>


                        <td>

                            #<?php echo $customer['id']; ?>

                        </td>

                    </tr>

                    <?php endwhile; ?>


                    <?php else: ?>

                    <tr>

                        <td colspan="5" class="empty">

                            <?php if ($search !== ""): ?>

                            No customer found for
                            "<strong><?php echo htmlspecialchars($search); ?></strong>".

                            <?php else: ?>

                            No customers have registered yet.

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