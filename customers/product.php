<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/config.php';

function productEscape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function productMoney($amount)
{
    return '₦' . number_format((float) $amount, 2);
}

function productOptions($value)
{
    $options = array_filter(
        array_map('trim', explode(',', (string) $value)),
        fn($option) => $option !== ''
    );

    return array_values(array_unique($options));
}

$productId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$productId || $productId < 1) {
    http_response_code(404);
    exit('Product not found. Please return to the shop.');
}

/*
|--------------------------------------------------------------------------
| Load the selected active product
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "SELECT id, name, description, price, category,
            size, color, stock, image
     FROM products
     WHERE id = ? AND status = 'active'
     LIMIT 1"
);

if (!$stmt) {
    http_response_code(500);
    exit('Unable to load this product right now.');
}

$stmt->bind_param('i', $productId);
$stmt->execute();

$result = $stmt->get_result();
$product = $result->fetch_assoc();

$stmt->close();

if (!$product) {
    http_response_code(404);
    exit('This product is unavailable or does not exist.');
}

$sizes = productOptions($product['size'] ?? '');
$colors = productOptions($product['color'] ?? '');
$stock = max(0, (int) $product['stock']);

$imageName = basename((string) ($product['image'] ?? ''));
$imageUrl = '../admin/uploads/products/' . rawurlencode($imageName);

$errorMessage = '';
$successMessage = '';

/*
|--------------------------------------------------------------------------
| Add item to session cart
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postedId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

    $selectedSize = trim($_POST['size'] ?? '');
    $selectedColor = trim($_POST['color'] ?? '');

    if (!$postedId || $postedId !== (int) $product['id']) {
        $errorMessage = 'Invalid product selection.';
    } elseif ($stock < 1) {
        $errorMessage = 'Sorry, this product is out of stock.';
    } elseif (!$quantity || $quantity < 1) {
        $errorMessage = 'Please select a valid quantity.';
    } elseif ($quantity > $stock) {
        $errorMessage = 'Only ' . $stock . ' item(s) are currently available.';
    } elseif (
        !empty($sizes) &&
        !in_array($selectedSize, $sizes, true)
    ) {
        $errorMessage = 'Please select a valid size.';
    } elseif (
        !empty($colors) &&
        !in_array($selectedColor, $colors, true)
    ) {
        $errorMessage = 'Please select a valid colour.';
    } else {
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // A separate cart entry allows different sizes/colours of one product.
        $cartKey = $product['id'] . '_' . md5(
            $selectedSize . '|' . $selectedColor
        );

        $existingQuantity = (int) (
            $_SESSION['cart'][$cartKey]['quantity'] ?? 0
        );

        if ($existingQuantity + $quantity > $stock) {
            $errorMessage = 'Your cart cannot contain more than ' .
                $stock . ' of this item.';
        } else {
            $_SESSION['cart'][$cartKey] = [
                'id'       => (int) $product['id'],
                'name'     => $product['name'],
                'price'    => (float) $product['price'],
                'image'    => $imageName,
                'quantity' => $existingQuantity + $quantity,
                'size'     => $selectedSize,
                'color'    => $selectedColor
            ];

            // Post/Redirect/Get prevents accidental duplicate submissions.
            header('Location: product.php?id=' . $productId . '&added=1');
            exit;
        }
    }
}

if (isset($_GET['added']) && $_GET['added'] === '1') {
    $successMessage = 'Product added to your cart successfully.';
}

/*
|--------------------------------------------------------------------------
| Cart count
|--------------------------------------------------------------------------
*/

$cartCount = 0;

foreach (($_SESSION['cart'] ?? []) as $cartItem) {
    if (is_array($cartItem)) {
        $cartCount += (int) ($cartItem['quantity'] ?? 0);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= productEscape($product['name']) ?> | Neems Fashion Cave
    </title>

    <link rel="stylesheet" href="css/product.css">
</head>

<body>

    <!-- NAVIGATION -->
    <header class="site-header">
        <div class="navbar">

            <a href="index.php" class="brand">
                <span class="brand-name">NEEMS</span>
                <span class="brand-subtitle">FASHION CAVE</span>
            </a>

            <nav class="nav-links">
                <a href="index.php">Home</a>
                <a href="shop.php" class="active">Shop</a>
                <a href="shop.php?category=Men">Men</a>
                <a href="shop.php?category=Women">Women</a>
                <a href="about.php">About</a>
                <a href="contact.php">Contact</a>
            </nav>

            <div class="nav-actions">
                <a href="cart.php" class="cart-link">
                    🛍 Cart
                    <span class="cart-count"><?= $cartCount ?></span>
                </a>
                <a href="login.php" class="account-link">Account</a>
            </div>

        </div>
    </header>

    <main class="page-container">

        <div class="breadcrumbs">
            <a href="index.php">Home</a>
            <span>/</span>
            <a href="shop.php">Shop</a>
            <span>/</span>
            <span><?= productEscape($product['name']) ?></span>
        </div>

        <?php if ($errorMessage !== ''): ?>
        <div class="notice error" role="alert">
            <?= productEscape($errorMessage) ?>
        </div>
        <?php endif; ?>

        <?php if ($successMessage !== ''): ?>
        <div class="notice success" role="status">
            <?= productEscape($successMessage) ?>
            <a href="cart.php">View cart →</a>
        </div>
        <?php endif; ?>

        <section class="product-layout">

            <!-- PRODUCT IMAGE -->
            <div class="product-gallery">

                <div class="main-image">
                    <?php if ($imageName !== ''): ?>
                    <img src="<?= productEscape($imageUrl) ?>" alt="<?= productEscape($product['name']) ?>"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="image-fallback" style="display:none;">
                        <span>👗</span>
                        <p>Product image unavailable</p>
                    </div>
                    <?php else: ?>
                    <div class="image-fallback">
                        <span>👗</span>
                        <p>No product image available</p>
                    </div>
                    <?php endif; ?>

                    <?php if ($stock < 1): ?>
                    <span class="image-badge sold-out">Out of Stock</span>
                    <?php else: ?>
                    <span class="image-badge">Neems Collection</span>
                    <?php endif; ?>
                </div>

                <p class="image-caption">
                    Style that speaks for you.
                </p>

            </div>

            <!-- PRODUCT DETAILS -->
            <div class="product-details">

                <p class="eyebrow">
                    <?= productEscape($product['category'] ?: 'NEEMS COLLECTION') ?>
                </p>

                <h1><?= productEscape($product['name']) ?></h1>

                <p class="product-price">
                    <?= productMoney($product['price']) ?>
                </p>

                <div class="detail-divider"></div>

                <div class="availability">
                    <span class="availability-dot <?= $stock > 0 ? 'available' : 'unavailable' ?>"></span>

                    <?php if ($stock > 0): ?>
                    <span>In stock — <?= $stock ?> available</span>
                    <?php else: ?>
                    <span>Currently out of stock</span>
                    <?php endif; ?>
                </div>

                <div class="description">
                    <h3>Product Description</h3>

                    <p>
                        <?= !empty($product['description'])
                        ? nl2br(productEscape($product['description']))
                        : 'Discover your style with this piece from the Neems Fashion Cave collection.' ?>
                    </p>
                </div>

                <form method="POST" action="product.php?id=<?= (int) $product['id'] ?>">

                    <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">

                    <?php if (!empty($sizes)): ?>
                    <div class="form-group">
                        <label for="size">Select Size</label>

                        <select name="size" id="size" required>
                            <option value="">Choose a size</option>

                            <?php foreach ($sizes as $size): ?>
                            <option value="<?= productEscape($size) ?>">
                                <?= productEscape($size) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($colors)): ?>
                    <div class="form-group">
                        <label for="color">Select Colour</label>

                        <select name="color" id="color" required>
                            <option value="">Choose a colour</option>

                            <?php foreach ($colors as $color): ?>
                            <option value="<?= productEscape($color) ?>">
                                <?= productEscape($color) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <div class="form-group quantity-group">
                        <label for="quantity">Quantity</label>

                        <div class="quantity-control">
                            <button type="button" id="decrease" aria-label="Decrease quantity">−</button>

                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?= $stock ?>"
                                required <?= $stock < 1 ? 'disabled' : '' ?>>

                            <button type="button" id="increase" aria-label="Increase quantity">+</button>
                        </div>
                    </div>

                    <button type="submit" class="add-to-cart" <?= $stock < 1 ? 'disabled' : '' ?>>
                        <?= $stock > 0 ? 'Add to Cart' : 'Out of Stock' ?>
                        <?php if ($stock > 0): ?>
                        <span>→</span>
                        <?php endif; ?>
                    </button>

                </form>

                <div class="product-promises">
                    <div>
                        <span>✓</span>
                        <p>Quality-selected fashion</p>
                    </div>
                    <div>
                        <span>✓</span>
                        <p>Secure shopping experience</p>
                    </div>
                    <div>
                        <span>✓</span>
                        <p>Customer support available</p>
                    </div>
                </div>

            </div>
        </section>

        <section class="back-to-shop">
            <div>
                <p class="eyebrow">FIND YOUR NEXT FAVOURITE</p>
                <h2>Explore More Styles</h2>
            </div>

            <a href="shop.php" class="continue-shopping">
                Continue Shopping →
            </a>
        </section>

    </main>

    <footer class="site-footer">
        <a href="index.php" class="footer-brand">NEEMS FASHION CAVE</a>

        <p>
            Discover your style with thoughtfully selected fashion.
        </p>

        <div class="footer-bottom">
            &copy; <?= date('Y') ?> Neems Fashion Cave. All rights reserved.
        </div>
    </footer>

    <script>
    const quantityInput = document.getElementById('quantity');
    const decreaseButton = document.getElementById('decrease');
    const increaseButton = document.getElementById('increase');

    if (quantityInput) {
        decreaseButton.addEventListener('click', () => {
            const minimum = Number(quantityInput.min) || 1;
            const current = Number(quantityInput.value) || minimum;

            quantityInput.value = Math.max(minimum, current - 1);
        });

        increaseButton.addEventListener('click', () => {
            const minimum = Number(quantityInput.min) || 1;
            const maximum = Number(quantityInput.max) || minimum;
            const current = Number(quantityInput.value) || minimum;

            quantityInput.value = Math.min(maximum, current + 1);
        });

        quantityInput.addEventListener('change', () => {
            const minimum = Number(quantityInput.min) || 1;
            const maximum = Number(quantityInput.max) || minimum;
            let value = Number(quantityInput.value) || minimum;

            value = Math.max(minimum, Math.min(maximum, value));
            quantityInput.value = value;
        });
    }
    </script>

</body>

</html>