<?php
session_start();

require_once __DIR__ . '/../includes/config.php';

$customerName = $_SESSION['customer_name'] ?? '';
$customerEmail = $_SESSION['customer_email'] ?? '';

$successMessage = '';
$errorMessage = '';

$name = $customerName;
$email = $customerEmail;
$subject = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (
        $name === '' ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        $subject === '' ||
        $message === ''
    ) {
        $errorMessage = 'Please complete every field with valid information.';
    } elseif (
        mb_strlen($name) > 100 ||
        mb_strlen($email) > 150 ||
        mb_strlen($subject) > 150 ||
        mb_strlen($message) > 5000
    ) {
        $errorMessage = 'One or more fields exceed the allowed length.';
    } else {
        /*
         * This form currently validates the message but does not save it.
         * Add database storage or email delivery to receive messages.
         */
        $successMessage = 'Thank you for contacting us! Your message has been validated. Please connect the form to email or database storage to ensure it reaches our team.';

        $name = '';
        $email = '';
        $subject = '';
        $message = '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Contact Us | Neems Fashion Cave</title>
    <meta name="description"
        content="Contact Neems Fashion Cave for questions about fashion, orders, deliveries and customer support.">

    <link rel="stylesheet" href="css/contact.css?v=1">
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
            <a href="contact.php" class="active">Contact</a>
        </nav>

        <div class="header-actions">
            <a href="account.php" class="account-link">My Account</a>
            <a href="cart.php" class="cart-link">Cart</a>
        </div>
    </header>

    <main>
        <section class="contact-hero">
            <div class="hero-content">
                <p class="eyebrow">WE'RE HERE FOR YOU</p>
                <h1>Let's talk <em>style.</em></h1>
                <p>
                    Have a question about an order, a product, or your next look?
                    We'd love to hear from you.
                </p>
            </div>
        </section>

        <section class="contact-section">
            <div class="contact-intro">
                <p class="eyebrow">GET IN TOUCH</p>
                <h2>We'd love to hear from you.</h2>
                <p>
                    Whether you need help choosing an outfit or have a question
                    about your purchase, send us a message and let us know how
                    we can help.
                </p>

                <div class="contact-details">
                    <div class="detail-item">
                        <div class="detail-icon">✉</div>
                        <div>
                            <h3>Email Us</h3>
                            <p>Replace with your official business email</p>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">☎</div>
                        <div>
                            <h3>Call Us</h3>
                            <p>Replace with your business phone number</p>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">⌖</div>
                        <div>
                            <h3>Our Location</h3>
                            <p>Add your shop address here</p>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">◷</div>
                        <div>
                            <h3>Business Hours</h3>
                            <p>Monday – Saturday · Add your opening hours</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="contact-form-card">
                <p class="eyebrow">SEND A MESSAGE</p>
                <h2>How can we help?</h2>

                <?php if ($successMessage !== ''): ?>
                <div class="alert success" role="status">
                    <?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?>
                </div>
                <?php endif; ?>

                <?php if ($errorMessage !== ''): ?>
                <div class="alert error" role="alert">
                    <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
                </div>
                <?php endif; ?>

                <form action="contact.php" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Your Name *</label>
                            <input type="text" id="name" name="name" maxlength="100" placeholder="Enter your full name"
                                value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>" autocomplete="name"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" maxlength="150" placeholder="you@example.com"
                                value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" autocomplete="email"
                                required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <select id="subject" name="subject" required>
                            <option value="">Choose a subject</option>
                            <option value="Order Enquiry" <?= $subject === 'Order Enquiry' ? 'selected' : '' ?>>Order
                                Enquiry</option>
                            <option value="Product Enquiry" <?= $subject === 'Product Enquiry' ? 'selected' : '' ?>>
                                Product Enquiry</option>
                            <option value="Delivery and Shipping"
                                <?= $subject === 'Delivery and Shipping' ? 'selected' : '' ?>>Delivery & Shipping
                            </option>
                            <option value="Returns and Exchanges"
                                <?= $subject === 'Returns and Exchanges' ? 'selected' : '' ?>>Returns & Exchanges
                            </option>
                            <option value="Other" <?= $subject === 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message">Your Message *</label>
                        <textarea id="message" name="message" rows="6" maxlength="5000"
                            placeholder="Tell us how we can help..."
                            required><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>

                    <button type="submit" class="submit-button">
                        Send Message <span>→</span>
                    </button>

                    <p class="form-note">
                        Fields marked with * are required.
                    </p>
                </form>
            </div>
        </section>

        <section class="contact-bottom">
            <p class="eyebrow">NEEMS FASHION CAVE</p>
            <h2>Great style. Personal service.</h2>
            <p>Thank you for making us part of your style journey.</p>
            <a href="shop.php" class="shop-button">Explore Our Collection →</a>
        </section>
    </main>

    <footer class="site-footer">
        <a href="index.php" class="footer-brand">NEEMS FASHION CAVE</a>
        <p>Style for every you.</p>

        <div class="footer-links">
            <a href="index.php">Home</a>
            <a href="shop.php">Shop</a>
            <a href="about.php">About Us</a>
            <a href="contact.php">Contact</a>
        </div>

        <p class="copyright">
            &copy; <?= date('Y') ?> Neems Fashion Cave. All rights reserved.
        </p>
    </footer>

</body>

</html>