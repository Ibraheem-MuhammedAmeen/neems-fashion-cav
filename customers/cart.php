<?php
session_start();

require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (empty($_SESSION['cart_csrf'])) {
    $_SESSION['cart_csrf'] = bin2hex(random_bytes(32));
}

$cart = &$_SESSION['cart'];
$errorMessage = '';

function escapeCart($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Handle cart updates and item removal.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';

    if (
        !is_string($token) ||
        !hash_equals($_SESSION['cart_csrf'], $token)
    ) {
        $errorMessage = 'Your session has expired. Please refresh the page and try again.';
    } else {
        $action = $_POST['action'] ?? '';

        if ($action === 'update') {
            $quantities = $_POST['quantities'] ?? [];

            if (!is_array($quantities)) {
                $errorMessage = 'Invalid quantity information.';
            } else {
                foreach ($quantities as $key => $quantity) {
                    if (!is_string($key) || !isset($cart[$key])) {
                        continue;
                    }

                    $newQuantity = filter_var(
                        $quantity,
                        FILTER_VALIDATE_INT,
                        ['options' => ['min_range' => 1, 'max_range' => 99]]
                    );

                    if ($newQuantity === false) {
                        $errorMessage = 'Quantities must be between 1 and 99.';
                        break;
                    }

                    $cart[$key]['quantity'] = $newQuantity;
                }
            }
        } elseif ($action === 'remove') {
            $key = $_POST['item_key'] ?? '';

            if (is_string($key) && isset($cart[$key])) {
                unset($cart[$key]);
            }
        } elseif ($action === 'clear') {
            $cart = [];
        }

        if ($errorMessage === '') {
            header('Location: cart.php');
            exit;
        }
    }
}

// Calculate cart totals.
$subtotal = 0;
$totalItems = 0;

foreach ($cart as $item) {
    $quantity = max(1, (int) ($item['quantity'] ?? 1));
    $price = max(0, (float) ($item['price'] ?? 0));

    $subtotal += $price * $quantity;
    $totalItems += $quantity;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Shopping Cart | Neems Fashion Cave</title>

    <link rel="stylesheet" href="css/cart.css?v=1">
</head>

<body>

    <header class="site-header">
        <a href="index.php" class="brand">
            <span class="brand-name">NEEMS</span>
            <span class="brand-subtitle">FASHION CAVE</span>
        </a>

        <nav class="main-nav">
            <a href="index.php">Home</a>
            <a href="shop.php">Shop</a>
            <a href="shop.php">Collections</a>
            <a href="about.php">About</a>
            <a href="contact.php">Contact</a>
        </nav>

        <div class="header-actions">
            <a href="account.php">My Account</a>
            <a href="cart.php" class="active">
                Cart (<?= (int) $totalItems ?>)
            </a>
        </div>
    </header>

    <main class="cart-page">

        <section class="page-heading">
            <p class="eyebrow">YOUR SELECTION</p>
            <h1>Shopping <em>Cart</em></h1>
            <p>Review your favourite pieces before checking out.</p>
        </section>

        <div class="breadcrumb">
            <a href="index.php">Home</a>
            <span>/</span>
            <a href="shop.php">Shop</a>
            <span>/</span>
            <span>Shopping Cart</span>
        </div>

        <?php if ($errorMessage !== ''): ?>
        <div class="alert error" role="alert">
            <?= escapeCart($errorMessage) ?>
        </div>
        <?php endif; ?>

        <?php if (empty($cart)): ?>

        <section class="empty-cart">
            <div class="empty-icon">♡</div>
            <h2>Your cart is waiting for something lovely.</h2>
            <p>
                You haven't added anything to your cart yet.
                Explore our collection and find your next favourite look.
            </p>
            <a href="shop.php" class="primary-button">
                Explore Collection →
            </a>
        </section>

        <?php else: ?>

        <div class="cart-layout">

            <section class="cart-items-section">

                <div class="cart-section-heading">
                    <h2>Your Items</h2>
                    <span><?= (int) $totalItems ?> item(s)</span>
                </div>

                <form action="cart.php" method="POST" id="cartForm">
                    <input type="hidden" name="csrf_token" value="<?= escapeCart($_SESSION['cart_csrf']) ?>">
                    <input type="hidden" name="action" value="update">

                    <div class="cart-table-heading">
                        <span>Product</span>
                        <span>Price</span>
                        <span>Quantity</span>
                        <span>Total</span>
                    </div>

                    <?php foreach ($cart as $key => $item): ?>
                    <?php
                        $productName = $item['name'] ?? 'Fashion Item';
                        $price = max(0, (float) ($item['price'] ?? 0));
                        $quantity = max(1, (int) ($item['quantity'] ?? 1));
                        $image = basename((string) ($item['image'] ?? ''));
                        $lineTotal = $price * $quantity;
                        ?>

                    <article class="cart-item">

                        <div class="product-info">
                            <a href="product.php?id=<?= (int) ($item['id'] ?? 0) ?>" class="product-image">
                                <?php if ($image !== ''): ?>
                                <img src="../admin/uploads/products/<?= rawurlencode($image) ?>"
                                    alt="<?= escapeCart($productName) ?>"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';">
                                <span class="image-placeholder">
                                    NEEMS
                                </span>
                                <?php else: ?>
                                <span class="image-placeholder visible">
                                    NEEMS
                                </span>
                                <?php endif; ?>
                            </a>

                            <div class="product-description">
                                <h3>
                                    <a href="product.php?id=<?= (int) ($item['id'] ?? 0) ?>">
                                        <?= escapeCart($productName) ?>
                                    </a>
                                </h3>

                                <?php if (!empty($item['size'])): ?>
                                <p>Size: <?= escapeCart($item['size']) ?></p>
                                <?php endif; ?>

                                <?php if (!empty($item['color'])): ?>
                                <p>Colour: <?= escapeCart($item['color']) ?></p>
                                <?php endif; ?>

                                <button type="submit" class="remove-button" form="remove-<?= escapeCart($key) ?>">
                                    Remove
                                </button>
                            </div>
                        </div>

                        <div class="mobile-label">Price</div>
                        <div class="item-price">
                            ₦<?= number_format($price, 2) ?>
                        </div>

                        <div class="mobile-label">Quantity</div>
                        <div class="quantity-control">
                            <input type="number" name="quantities[<?= escapeCart($key) ?>]" min="1" max="99"
                                value="<?= $quantity ?>" aria-label="Quantity for <?= escapeCart($productName) ?>">
                        </div>

                        <div class="mobile-label">Total</div>
                        <div class="line-total">
                            ₦<?= number_format($lineTotal, 2) ?>
                        </div>

                    </article>

                    <?php endforeach; ?>

                    <div class="cart-actions">
                        <a href="shop.php" class="continue-shopping">
                            ← Continue Shopping
                        </a>

                        <button type="submit" class="update-button">
                            Update Cart
                        </button>
                    </div>

                </form>

                <?php foreach (array_keys($cart) as $key): ?>
                <form id="remove-<?= escapeCart($key) ?>" action="cart.php" method="POST" class="hidden-form">
                    <input type="hidden" name="csrf_token" value="<?= escapeCart($_SESSION['cart_csrf']) ?>">
                    <input type="hidden" name="action" value="remove">
                    <input type="hidden" name="item_key" value="<?= escapeCart($key) ?>">
                </form>
                <?php endforeach; ?>

                <form action="cart.php" method="POST" class="clear-cart-form">
                    <input type="hidden" name="csrf_token" value="<?= escapeCart($_SESSION['cart_csrf']) ?>">
                    <input type="hidden" name="action" value="clear">

                    <button type="submit" class="clear-cart-button"
                        onclick="return confirm('Are you sure you want to remove all items from your cart?');">
                        Clear Cart
                    </button>
                </form>

            </section>

            <aside class="order-summary">
                <h2>Order Summary</h2>

                <div class="summary-line">
                    <span>Subtotal</span>
                    <strong>₦<?= number_format($subtotal, 2) ?></strong>
                </div>

                <div class="summary-line">
                    <span>Delivery</span>
                    <span class="calculated-later">At checkout</span>
                </div>

                <div class="summary-divider"></div>

                <div class="summary-line grand-total">
                    <span>Subtotal</span>
                    <strong>₦<?= number_format($subtotal, 2) ?></strong>
                </div>

                <p class="summary-note">
                    Delivery charges and any applicable fees will be confirmed
                    during checkout.
                </p>

                <a href="checkout.php" class="checkout-button">
                    Proceed to Checkout →
                </a>

                <div class="secure-note">
                    <span>✓</span>
                    Review your order before payment.
                </div>

                <a href="shop.php" class="back-to-shop">
                    Continue Shopping
                </a>
            </aside>

        </div>

        <?php endif; ?>

    </main>

    <footer class="site-footer">
        <a href="index.php" class="footer-brand">NEEMS FASHION CAVE</a>
        <p>Style for every you.</p>

        <div class="footer-links">
            <a href="index.php">Home</a>
            <a href="shop.php">Shop</a>
            <a href="about.php">About</a>
            <a href="contact.php">Contact</a>
        </div>

        <p class="copyright">
            &copy; <?= date('Y') ?> Neems Fashion Cave. All rights reserved.
        </p>
    </footer>

</body>

</html>