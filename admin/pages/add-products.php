<?php

include('../../includes/config.php');
include('./auth.php');

$message = "";
$error = "";

if (isset($_POST['add_product'])) {

    // Get form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $category = $_POST['category'];
    $size = $_POST['size'];
    $color = trim($_POST['color']);
    $stock = $_POST['stock'];
    $status = $_POST['status'];

    // Check if an image was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

        $image = $_FILES['image'];

        $imageName = $image['name'];
        $imageTmpName = $image['tmp_name'];
        $imageSize = $image['size'];

        // Get file extension
        $imageExtension = strtolower(
            pathinfo($imageName, PATHINFO_EXTENSION)
        );

        // Allowed image types
        $allowedExtensions = [
            'jpg',
            'jpeg',
            'png',
            'webp'
        ];

        if (!in_array($imageExtension, $allowedExtensions)) {

            $error = "Only JPG, JPEG, PNG and WEBP images are allowed.";

        } elseif ($imageSize > 5 * 1024 * 1024) {

            $error = "Image size must not exceed 5MB.";

        } else {

            // Create a unique image name
            $newImageName = uniqid('product_', true)
                . '.'
                . $imageExtension;

            // Upload location
            $uploadPath = '../uploads/products/' . $newImageName;

            if (move_uploaded_file($imageTmpName, $uploadPath)) {

                // Insert product into database
                $sql = "
                    INSERT INTO products
                    (
                        name,
                        description,
                        price,
                        category,
                        size,
                        color,
                        stock,
                        image,
                        status
                    )
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ";

                $stmt = $conn->prepare($sql);

                if ($stmt) {

                    $stmt->bind_param(
                        "ssdsssiss",
                        $name,
                        $description,
                        $price,
                        $category,
                        $size,
                        $color,
                        $stock,
                        $newImageName,
                        $status
                    );

                    if ($stmt->execute()) {

                        $message = "Product added successfully.";

                    } else {

                        $error = "Failed to add product to database.";

                        // Remove uploaded image if database insert fails
                        if (file_exists($uploadPath)) {
                            unlink($uploadPath);
                        }
                    }

                    $stmt->close();

                } else {

                    $error = "Database query could not be prepared.";

                    // Remove uploaded image
                    if (file_exists($uploadPath)) {
                        unlink($uploadPath);
                    }
                }

            } else {

                $error = "Failed to upload image.";
            }
        }

    } else {

        $error = "Please select a product image.";
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Product | Neems Fashion Cave</title>

    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/add-products.css">

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

            <a href="add-products.php" class="active">
                Add Product
            </a>

            <a href="orders.php">
                Orders
            </a>

            <a href="customers.php">
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

                <h1>Add Product</h1>

                <p>
                    Add a new product to your store
                </p>

            </div>

            <div class="admin-profile">

                <div class="avatar">

                    <?php

                    echo strtoupper(
                        substr($_SESSION['admin_name'], 0, 1)
                    );

                    ?>

                </div>

                <span>

                    <?php

                    echo htmlspecialchars(
                        $_SESSION['admin_name']
                    );

                    ?>

                </span>

            </div>

        </header>


        <!-- FORM -->

        <div class="form-container">

            <h2>Product Information</h2>

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


            <form method="POST" enctype="multipart/form-data" class="product-form">

                <!-- PRODUCT NAME -->

                <div class="form-group full">

                    <label for="name">
                        Product Name
                    </label>

                    <input type="text" id="name" name="name" placeholder="e.g. Classic Ankara Shirt" required>

                </div>


                <!-- DESCRIPTION -->

                <div class="form-group full">

                    <label for="description">
                        Description
                    </label>

                    <textarea id="description" name="description" placeholder="Enter product description..."></textarea>

                </div>


                <!-- PRICE -->

                <div class="form-group">

                    <label for="price">
                        Price (₦)
                    </label>

                    <input type="number" id="price" name="price" step="0.01" min="0" placeholder="25000" required>

                </div>


                <!-- CATEGORY -->

                <div class="form-group">

                    <label for="category">
                        Category
                    </label>

                    <select id="category" name="category" required>

                        <option value="">
                            Select Category
                        </option>

                        <option value="Men">
                            Men
                        </option>

                        <option value="Women">
                            Women
                        </option>

                        <option value="Kids">
                            Kids
                        </option>

                        <option value="Shoes">
                            Shoes
                        </option>

                        <option value="Accessories">
                            Accessories
                        </option>

                    </select>

                </div>


                <!-- SIZE -->

                <div class="form-group">

                    <label for="size">
                        Size
                    </label>

                    <select id="size" name="size">

                        <option value="">
                            Select Size
                        </option>

                        <option value="XS">
                            XS
                        </option>

                        <option value="S">
                            S
                        </option>

                        <option value="M">
                            M
                        </option>

                        <option value="L">
                            L
                        </option>

                        <option value="XL">
                            XL
                        </option>

                        <option value="XXL">
                            XXL
                        </option>

                        <option value="Free Size">
                            Free Size
                        </option>

                    </select>

                </div>


                <!-- COLOR -->

                <div class="form-group">

                    <label for="color">
                        Color
                    </label>

                    <input type="text" id="color" name="color" placeholder="e.g. Black">

                </div>


                <!-- STOCK -->

                <div class="form-group">

                    <label for="stock">
                        Stock Quantity
                    </label>

                    <input type="number" id="stock" name="stock" min="0" placeholder="10" required>

                </div>


                <!-- STATUS -->

                <div class="form-group">

                    <label for="status">
                        Product Status
                    </label>

                    <select id="status" name="status">

                        <option value="active">
                            Active
                        </option>

                        <option value="inactive">
                            Inactive
                        </option>

                    </select>

                </div>


                <!-- IMAGE -->

                <div class="form-group full">

                    <label for="image">
                        Product Image
                    </label>

                    <input class="image-input" type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp"
                        required>

                    <small>
                        JPG, JPEG, PNG or WEBP. Maximum 5MB.
                    </small>

                </div>


                <!-- SUBMIT -->

                <div class="submit-area">

                    <button type="submit" name="add_product" class="submit-btn">
                        Add Product
                    </button>

                </div>

            </form>

        </div>

    </main>

</body>

</html>