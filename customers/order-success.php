<?php
session_start();

require_once __DIR__ . '/../includes/config.php';

// Get order ID from the checkout redirect
$orderId = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);

// If no valid order ID exists, show a general confirmation
$orderNumber = $orderId ? '#' . $orderId : 'Confirmed';

// Get customer details if available
$customerName = $_SESSION['customer_name'] ?? 'Valued Customer';

// Get order details saved by checkout, if available
$orderTotal = $_SESSION['completed_order_total'] ?? null;

// Clear checkout-related cart data only after a successful order
// Uncomment this if your checkout code has already saved the order.
// unset($_SESSION['cart']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Order Successful | Neems Fashion Cave</title>

    <link rel="stylesheet" href="css/order-success.css?v=1">
</head>

<body>

    <header class="success-header">
        <a href="index.php" class="brand">
            <span class="brand-name">NEEMS</span>
            <span class="brand-subtitle">FASHION CAVE</span>
        </a>

        <a href="shop.php" class="continue-link">Continue Shopping</a>
    </header>

    <main class="success-container">

        <div class="success-icon">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M5 12.5l4.5 4.5L19 7"></path>
            </svg>
        </div>

        <p class="eyebrow">ORDER CONFIRMATION</p>

        <h1>Thank you for your order!</h1>

        <p class="success-message">
            Hello <?= htmlspecialchars($customerName, ENT_QUOTES, 'UTF-8') ?>,
            your order has been placed successfully.
            Thank you for shopping with Neems Fashion Cave.
        </p>

        <section class="order-card">
            <div class="order-detail">
                <span>Order Number</span>
                <strong><?= htmlspecialchars($orderNumber, ENT_QUOTES, 'UTF-8') ?></strong>
            </div>

            <?php if ($orderTotal !== null): ?>
            <div class="order-detail">
                <span>Order Total</span>
                <strong>
                    ₦<?= number_format((float) $orderTotal, 2) ?>
                </strong>
            </div>
            <?php endif; ?>

            <div class="order-detail">
                <span>Order Status</span>
                <strong class="status">Order Placed</strong>
            </div>
        </section>

        <p class="next-step">
            We'll process your order and prepare your items.
            Please keep your order number for future reference.
        </p>

        <div class="success-actions">
            <a href="shop.php" class="primary-button">Continue Shopping</a>
            <a href="orders.php" class="secondary-button">View My Orders</a>
        </div>

        <a href="index.php" class="home-link">← Back to Home</a>

    </main>

    <footer class="success-footer">
        <p>&copy; <?= date('Y') ?> Neems Fashion Cave. All rights reserved.</p>
    </footer>

</body>

</html>