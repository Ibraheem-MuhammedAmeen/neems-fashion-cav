<?php

include('../../includes/config.php');
include('./auth.php');


// SEARCH


$search = "";

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}


// GET PRODUCTS
if ($search !== "") {

    $sql = "
        SELECT *
        FROM products
        WHERE name LIKE ?
        OR category LIKE ?
        OR color LIKE ?
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
        SELECT *
        FROM products
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

    <title>Manage Products | Neems Fashion Cave</title>

    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/manage-products.css">


</head>


<body>


    <!-- =========================
     SIDEBAR
========================= -->

    <aside class="sidebar">

        <div class="logo">

            <h2>NEEMS</h2>

            <p>Fashion Cave</p>

        </div>


        <nav>

            <a href="index.php">

                Dashboard

            </a>


            <a href="manage-products.php" class="active">

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



    <!-- =========================
     MAIN CONTENT
========================= -->

    <main class="main">


        <!-- PAGE HEADER -->

        <div class="page-header">

            <div>

                <h1>Manage Products</h1>

                <p>

                    View, edit and manage your store products.

                </p>

            </div>


            <a href="add-product.php" class="add-btn">

                + Add Product

            </a>

        </div>



        <!-- SEARCH -->

        <div class="search-box">

            <form method="GET" class="search-form">

                <input type="text" name="search" placeholder="Search by product name, category or color..."
                    value="<?php echo htmlspecialchars($search); ?>">

                <button type="submit">

                    Search

                </button>


                <?php if ($search !== ""): ?>

                <a href="manage-products.php" class="clear-btn">

                    Clear

                </a>

                <?php endif; ?>

            </form>

        </div>



        <!-- PRODUCTS -->

        <div class="products-container">

            <table class="products-table">

                <thead>

                    <tr>

                        <th>Image</th>

                        <th>Product</th>

                        <th>Category</th>

                        <th>Price</th>

                        <th>Size</th>

                        <th>Color</th>

                        <th>Stock</th>

                        <th>Status</th>

                        <th>Actions</th>

                    </tr>

                </thead>


                <tbody>


                    <?php if ($result && $result->num_rows > 0): ?>


                    <?php while ($product = $result->fetch_assoc()): ?>


                    <tr>


                        <!-- IMAGE -->

                        <td>

                            <?php

                            $imagePath =
                                "../uploads/products/"
                                . $product['image'];

                            ?>

                            <?php if (
                                !empty($product['image'])
                                && file_exists($imagePath)
                            ): ?>

                            <img src="<?php echo htmlspecialchars($imagePath); ?>"
                                alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">

                            <?php else: ?>

                            <div class="product-image" style="
                                        display:flex;
                                        align-items:center;
                                        justify-content:center;
                                        background:#eee;
                                    ">

                                No Image

                            </div>

                            <?php endif; ?>

                        </td>



                        <!-- PRODUCT -->

                        <td>

                            <div class="product-name">

                                <?php

                                echo htmlspecialchars(
                                    $product['name']
                                );

                                ?>

                            </div>


                            <div class="product-description">

                                <?php

                                echo htmlspecialchars(
                                    $product['description']
                                );

                                ?>

                            </div>

                        </td>



                        <!-- CATEGORY -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $product['category']
                            );

                            ?>

                        </td>



                        <!-- PRICE -->

                        <td>

                            ₦<?php

                            echo number_format(
                                $product['price'],
                                2
                            );

                            ?>

                        </td>



                        <!-- SIZE -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $product['size']
                            );

                            ?>

                        </td>



                        <!-- COLOR -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $product['color']
                            );

                            ?>

                        </td>



                        <!-- STOCK -->

                        <td>

                            <?php if ($product['stock'] <= 5): ?>

                            <span class="stock-low">

                                <?php echo $product['stock']; ?>

                            </span>

                            <?php else: ?>

                            <span class="stock-good">

                                <?php echo $product['stock']; ?>

                            </span>

                            <?php endif; ?>

                        </td>



                        <!-- STATUS -->

                        <td>

                            <?php if (
                                $product['status'] === 'active'
                            ): ?>

                            <span class="status active">

                                Active

                            </span>

                            <?php else: ?>

                            <span class="status inactive">

                                Inactive

                            </span>

                            <?php endif; ?>

                        </td>



                        <!-- ACTIONS -->

                        <td>

                            <div class="actions-cell">


                                <a href="edit-product.php?id=<?php echo $product['id']; ?>" class="edit-btn">

                                    Edit

                                </a>


                                <a href="delete-product.php?id=<?php echo $product['id']; ?>" class="delete-btn"
                                    onclick="return confirm(
                                        'Are you sure you want to delete this product?'
                                    );">

                                    Delete

                                </a>


                            </div>

                        </td>


                    </tr>


                    <?php endwhile; ?>


                    <?php else: ?>


                    <tr>

                        <td colspan="9" class="empty">

                            <?php if ($search !== ""): ?>

                            No products found for
                            "<strong><?php echo htmlspecialchars($search); ?></strong>".

                            <?php else: ?>

                            No products have been added yet.

                            <?php endif; ?>


                            <br><br>


                            <a href="add-product.php">

                                Add your first product

                            </a>

                        </td>

                    </tr>


                    <?php endif; ?>


                </tbody>

            </table>

        </div>


    </main>


</body>

</html>
```