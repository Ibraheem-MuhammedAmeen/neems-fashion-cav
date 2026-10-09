<?php
session_start();

require_once __DIR__ . '/../includes/config.php';

// Load active products from the admin database.
$sql = "SELECT id, name, description, price, category, size,
               color, stock, image
        FROM products
        WHERE status = 'active'
        ORDER BY created_at DESC
        LIMIT 8";

$result = $conn->query($sql);

$products = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Cart count, assuming cart items use a quantity field.
$cartCount = 0;
$cart = $_SESSION['cart'] ?? [];

foreach ($cart as $item) {
    if (is_array($item)) {
        $cartCount += (int) ($item['quantity'] ?? 0);
    }
}

// Customer image location.
// admin/uploads/products contains the images uploaded in your admin page.
$imageBaseUrl = '../admin/uploads/products/';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Neems Fashion Cave | Elevate Your Style</title>

    <meta name="description" content="Discover stylish menswear and womenswear at Neems Fashion Cave.">

    <link rel="stylesheet" href="css/home.css">
</head>

<body>

    <!-- ANNOUNCEMENT BAR -->
    <div class="announcement">
        YOUR STYLE, YOUR STATEMENT — WELCOME TO NEEMS
    </div>

    <!-- NAVIGATION -->
    <header class="navbar">
        <a href="index.php" class="brand">
            <span class="brand-name">NEEMS</span>
            <span class="brand-subtitle">FASHION CAVE</span>
        </a>

        <nav class="nav-links">
            <a href="#collection">All</a>
            <a href="#collection" data-category="Men">Men</a>
            <a href="#collection" data-category="Women">Women</a>
            <a href="#about">About</a>
            <a href="#contact">Contact</a>
        </nav>

        <div class="nav-actions">
            <a href="search.php" class="icon-link" aria-label="Search">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="10.8" cy="10.8" r="6.8"></circle>
                    <path d="m16 16 5 5"></path>
                </svg>
            </a>

            <a href="cart.php" class="cart-link">
                Cart <span>(<?= $cartCount ?>)</span>
            </a>

            <a href="login.php" class="account-link">Account</a>

            <button class="menu-toggle" id="menuToggle" aria-label="Toggle navigation" aria-expanded="false">☰</button>
        </div>
    </header>

    <!-- HERO -->
    <main>
        <section class="hero">
            <div class="hero-content">
                <p class="eyebrow">THE NEEMS COLLECTION</p>

                <h1>
                    Elevate Your Style
                    <span>This Season.</span>
                </h1>

                <p class="hero-description">
                    Discover curated collections of premium menswear
                    and womenswear. Crafted for comfort, designed for confidence.
                </p>

                <div class="hero-buttons">
                    <a href="#collection" class="button button-dark">
                        Shop Now <span>↗</span>
                    </a>

                    <a href="#about" class="button button-outline">
                        Our Story
                    </a>
                </div>

                <div class="hero-note">
                    <span class="note-line"></span>
                    STYLE THAT SPEAKS WITHOUT WORDS
                </div>
            </div>

            <div class="hero-image">
                <img src="images/hero-fashion.png" alt="The latest fashion collection from Neems"
                    onerror="this.style.display='none'; this.parentElement.classList.add('hero-fallback');">

                <div class="hero-image-label">
                    <span>NEEMS</span>
                    <span>STYLE / QUALITY / CONFIDENCE</span>
                </div>
            </div>
        </section>

        <!-- COLLECTION -->
        <section class="collection section" id="collection">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">CURATED FOR YOU</p>
                    <h2>Neems Collection<span>.</span></h2>
                    <p class="section-description">
                        Handpicked styles for every occasion.
                    </p>
                </div>

                <a href="products.php" class="text-link">
                    View All Products ↗
                </a>
            </div>

            <div class="category-filters">
                <button class="filter-btn active" data-filter="All">
                    All Items
                </button>

                <button class="filter-btn" data-filter="Men">
                    Men's Clothing
                </button>

                <button class="filter-btn" data-filter="Women">
                    Women's Clothing
                </button>

                <button class="filter-btn" data-filter="Kids">
                    Kids
                </button>

                <button class="filter-btn" data-filter="Shoes">
                    Shoes
                </button>

                <button class="filter-btn" data-filter="Accessories">
                    Accessories
                </button>
            </div>

            <div class="product-grid">
                <?php if (!empty($products)): ?>

                <?php foreach ($products as $product): ?>
                <?php
                        $category = $product['category'] ?? '';
                        $image = $imageBaseUrl . rawurlencode(
                            basename($product['image'] ?? '')
                        );

                        $hasImage = !empty($product['image']);
                        $inStock = (int) $product['stock'] > 0;
                        ?>

                <article class="product-card" data-category="<?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?>">

                    <a class="product-image" href="product-details.php?id=<?= (int) $product['id'] ?>">

                        <?php if ($hasImage): ?>
                        <img src="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>"
                            alt="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>" loading="lazy"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';">
                        <div class="image-placeholder" style="display:none">NEEMS</div>
                        <?php else: ?>
                        <div class="image-placeholder">NEEMS</div>
                        <?php endif; ?>

                        <?php if (!$inStock): ?>
                        <span class="product-badge sold-out">
                            Sold Out
                        </span>
                        <?php else: ?>
                        <span class="product-badge">NEEMS EDIT</span>
                        <?php endif; ?>
                    </a>

                    <div class="product-info">
                        <p class="product-category">
                            <?= htmlspecialchars($category ?: 'Collection', ENT_QUOTES, 'UTF-8') ?>
                        </p>

                        <h3>
                            <a href="product-details.php?id=<?= (int) $product['id'] ?>">
                                <?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        </h3>

                        <div class="product-bottom">
                            <p class="product-price">
                                ₦<?= number_format((float) $product['price'], 2) ?>
                            </p>

                            <?php if ($inStock): ?>
                            <a class="add-link" href="product-details.php?id=<?= (int) $product['id'] ?>">
                                View Item +
                            </a>
                            <?php else: ?>
                            <span class="unavailable">
                                Unavailable
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>

                <?php else: ?>
                <div class="empty-products">
                    <h3>Your next favourite look starts here.</h3>
                    <p>New pieces will appear here when they are added to the store.</p>
                </div>
                <?php endif; ?>
            </div>

            <div class="collection-cta">
                <a href="products.php" class="button button-dark">
                    Explore The Collection ↗
                </a>
            </div>
        </section>

        <!-- ABOUT -->
        <section class="about-section" id="about">
            <div class="about-mark">N.</div>

            <div class="about-content">
                <p class="eyebrow">A LITTLE ABOUT US</p>
                <h2>Style is personal.<br>Make it yours.</h2>

                <p>
                    At Neems Fashion Cave, we believe great style is more
                    than what you wear. It is how you express yourself.
                    Our collection brings together comfort, confidence,
                    and pieces that make every day feel a little more special.
                </p>

                <a href="about.php" class="text-link">
                    Discover Our Story ↗
                </a>
            </div>
        </section>

        <!-- SHOPPING BENEFITS -->
        <section class="benefits section">
            <div class="benefit">
                <span class="benefit-number">01</span>
                <h3>Quality First</h3>
                <p>Thoughtfully selected pieces for your wardrobe.</p>
            </div>

            <div class="benefit">
                <span class="benefit-number">02</span>
                <h3>Style for Everyone</h3>
                <p>Explore looks for different moods and occasions.</p>
            </div>

            <div class="benefit">
                <span class="benefit-number">03</span>
                <h3>Easy Shopping</h3>
                <p>Browse the collection and find your next favourite.</p>
            </div>

            <div class="benefit">
                <span class="benefit-number">04</span>
                <h3>Here to Help</h3>
                <p>Reach out whenever you need assistance.</p>
            </div>
        </section>
    </main>

    <!-- FOOTER -->
    <footer class="footer" id="contact">
        <div class="footer-main">
            <div class="footer-brand">
                <a href="index.php" class="brand footer-logo">
                    <span class="brand-name">NEEMS</span>
                    <span class="brand-subtitle">FASHION CAVE</span>
                </a>

                <p>
                    Modern fashion. Timeless confidence.
                    Find the pieces that feel like you.
                </p>
            </div>

            <div class="footer-column">
                <h3>Shop</h3>
                <a href="products.php">All Products</a>
                <a href="products.php?category=Men">Men</a>
                <a href="products.php?category=Women">Women</a>
                <a href="products.php?category=Accessories">Accessories</a>
            </div>

            <div class="footer-column">
                <h3>Explore</h3>
                <a href="#about">Our Story</a>
                <a href="login.php">My Account</a>
                <a href="cart.php">Shopping Cart</a>
                <a href="contact.php">Contact Us</a>
            </div>

            <div class="footer-column">
                <h3>Get in Touch</h3>
                <a href="mailto:neemsfashioncave@gmail.com">
                    neemsfashioncave@gmail.com
                </a>
                <p>Abuja, Nigeria</p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© <?= date('Y') ?> Neems Fashion Cave. All rights reserved.</p>
            <p>MADE FOR YOUR STYLE.</p>
        </div>
    </footer>

    <script>
    // Category filtering
    const filterButtons = document.querySelectorAll('.filter-btn');
    const productCards = document.querySelectorAll('.product-card');

    function filterProducts(category) {
        filterButtons.forEach(button => {
            button.classList.toggle(
                'active',
                button.dataset.filter === category
            );
        });

        productCards.forEach(card => {
            const matches = category === 'All' ||
                card.dataset.category.toLowerCase() === category.toLowerCase();

            card.style.display = matches ? '' : 'none';
        });
    }

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            filterProducts(button.dataset.filter);
        });
    });

    // Header category links
    document.querySelectorAll('.nav-links [data-category]').forEach(link => {
        link.addEventListener('click', () => {
            filterProducts(link.dataset.category);
        });
    });

    // Mobile navigation
    const menuToggle = document.getElementById('menuToggle');
    const navLinks = document.querySelector('.nav-links');

    menuToggle.addEventListener('click', () => {
        const isOpen = navLinks.classList.toggle('nav-open');
        menuToggle.setAttribute('aria-expanded', String(isOpen));
    });

    navLinks.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            navLinks.classList.remove('nav-open');
            menuToggle.setAttribute('aria-expanded', 'false');
        });
    });
    </script>

</body>

</html>