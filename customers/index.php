<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/config.php';

function homeEscape($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function homeMoney($amount): string
{
    return '₦' . number_format((float) $amount, 2);
}

// Shopping cart count
$cartCount = 0;

if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $cartItem) {
        if (is_array($cartItem)) {
            $cartCount += (int) ($cartItem['quantity'] ?? 0);
        }
    }
}

// Latest active products from the database
$products = [];

$sql = "SELECT id, name, description, price, category, image, stock
        FROM products
        WHERE status = 'active'
        ORDER BY id DESC
        LIMIT 8";

$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$categories = [
    [
        'name' => 'Women',
        'description' => 'Effortless looks, made memorable.',
        'image' => 'women-fashion.png',
        'link' => 'shop.php?category=Women',
        'number' => '01'
    ],
    [
        'name' => 'Men',
        'description' => 'Everyday style with character.',
        'image' => 'men-fashion.png',
        'link' => 'shop.php?category=Men',
        'number' => '02'
    ],
    [
        'name' => 'Kids',
        'description' => 'Little looks, big personalities.',
        'image' => 'kids-fashion.png',
        'link' => 'shop.php?category=Kids',
        'number' => '03'
    ],
    [
        'name' => 'Shoes & Accessories',
        'description' => 'The details complete the look.',
        'image' => 'accessories-fashion.jpg',
        'link' => 'shop.php?category=Accessories',
        'number' => '04'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Neems Fashion Cave | Fashion With Feeling</title>

    <meta name="description"
        content="Explore clothing, shoes and accessories at Neems Fashion Cave. Find your style and shop the latest collection.">

    <link rel="stylesheet" href="css/home.css">
    <script src="js/home.js" defer></script>
    <link rel="stylesheet" href="css/home.css?v=3">
    <script src="js/home.js?v=3" defer></script>
</head>

<body>

    <!-- Announcement -->
    <div class="announcement-bar">
        <p>YOUR STYLE. YOUR STORY. YOUR NEEMS.</p>
        <a href="shop.php">Discover the collection <span>↗</span></a>
    </div>

    <!-- Header -->
    <header class="site-header">
        <nav class="navbar">

            <a href="index.php" class="brand" aria-label="Neems Fashion Cave home">
                <span class="brand-symbol">N.</span>
                <span class="brand-copy">
                    <strong>NEEMS</strong>
                    <small>FASHION CAVE</small>
                </span>
            </a>

            <button class="menu-toggle" id="menuToggle" type="button" aria-label="Open navigation"
                aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <div class="nav-links" id="navLinks">
                <a href="index.php" class="nav-link active">Home</a>
                <a href="shop.php" class="nav-link">Shop</a>
                <a href="shop.php" class="nav-link">Products</a>

                <div class="nav-dropdown">
                    <button class="dropdown-toggle" type="button" aria-expanded="false">
                        Categories <span class="dropdown-chevron">⌄</span>
                    </button>

                    <div class="dropdown-menu">
                        <div class="dropdown-heading">SHOP BY CATEGORY</div>
                        <a href="shop.php?category=Women">Women <span>↗</span></a>
                        <a href="shop.php?category=Men">Men <span>↗</span></a>
                        <a href="shop.php?category=Kids">Kids <span>↗</span></a>
                        <a href="shop.php?category=Shoes">Shoes <span>↗</span></a>
                        <a href="shop.php?category=Accessories">Accessories <span>↗</span></a>
                        <a href="shop.php" class="dropdown-footer">Explore everything →</a>
                    </div>
                </div>

                <a href="about.php" class="nav-link">About</a>
                <a href="contact.php" class="nav-link">Contact</a>
            </div>

            <div class="nav-actions">
                <a href="login.php" class="icon-link" aria-label="My account" title="My account">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="8" r="3.5"></circle>
                        <path d="M5 21v-2a7 7 0 0 1 14 0v2"></path>
                    </svg>
                </a>

                <a href="cart.php" class="cart-link" aria-label="Shopping cart">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M3 4h2l2.2 11a2 2 0 0 0 2 1.6h8.5a2 2 0 0 0 2-1.6L21 8H6"></path>
                        <circle cx="10" cy="21" r="1"></circle>
                        <circle cx="18" cy="21" r="1"></circle>
                    </svg>
                    <span class="cart-count"><?= $cartCount ?></span>
                </a>
            </div>
        </nav>
    </header>

    <main>

        <!-- Hero -->
        <section class="hero">
            <div class="hero-background"></div>
            <div class="hero-grain"></div>

            <div class="hero-content">
                <div class="hero-kicker">
                    <span class="kicker-line"></span>
                    THE NEEMS EDIT
                </div>

                <h1>Wear what<br>makes you <em>feel.</em></h1>

                <p>
                    Style isn't about fitting in. It's about finding the pieces
                    that feel like you, wherever life takes you.
                </p>

                <div class="hero-actions">
                    <a href="shop.php" class="button button-light">
                        Shop the collection <span>↗</span>
                    </a>
                    <a href="#collections" class="hero-text-link">
                        Explore our world <span>↓</span>
                    </a>
                </div>

                <div class="hero-note">
                    <span class="hero-note-number">01</span>
                    <span>CONFIDENCE IN EVERY THREAD</span>
                </div>
            </div>

            <div class="hero-side-label">STYLE · EXPRESSION · YOU</div>

            <div class="hero-bottom">
                <span>CURATED FOR YOUR EVERYDAY</span>
                <span>NEEMS FASHION CAVE — ABUJA</span>
            </div>
        </section>

        <!-- Brand values -->
        <section class="value-strip">
            <div class="value-item">
                <span class="value-number">01</span>
                <div>
                    <strong>Thoughtful Style</strong>
                    <p>Pieces chosen with care</p>
                </div>
            </div>

            <div class="value-item">
                <span class="value-number">02</span>
                <div>
                    <strong>For Every You</strong>
                    <p>Find a look that feels right</p>
                </div>
            </div>

            <div class="value-item">
                <span class="value-number">03</span>
                <div>
                    <strong>Easy to Explore</strong>
                    <p>Your next favourite, just a click away</p>
                </div>
            </div>
        </section>

        <!-- Categories -->
        <section class="collections-section section-wrap" id="collections">
            <div class="section-topline">
                <span>01 / THE COLLECTIONS</span>
                <span>FIND YOUR OWN KIND OF STYLE</span>
            </div>

            <div class="section-heading">
                <div>
                    <p class="eyebrow">MADE FOR YOUR MOMENTS</p>
                    <h2>A world of<br><em>your own.</em></h2>
                </div>

                <p class="heading-description">
                    From everyday essentials to the pieces that turn heads,
                    start with the collection that speaks to you.
                </p>
            </div>

            <div class="collection-grid">
                <?php foreach ($categories as $category): ?>
                <a href="<?= homeEscape($category['link']) ?>" class="collection-card">

                    <img src="images/<?= homeEscape($category['image']) ?>"
                        alt="<?= homeEscape($category['name']) ?> collection" loading="lazy"
                        onerror="this.style.display='none';">

                    <div class="collection-overlay"></div>

                    <div class="collection-number">
                        <?= homeEscape($category['number']) ?>
                    </div>

                    <div class="collection-content">
                        <p><?= homeEscape($category['description']) ?></p>
                        <h3><?= homeEscape($category['name']) ?></h3>
                        <span class="collection-link">Explore collection <b>↗</b></span>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Products -->
        <section class="products-section section-wrap" id="products">
            <div class="section-topline">
                <span>02 / THE LATEST</span>
                <span>FRESH FINDS, JUST FOR YOU</span>
            </div>

            <div class="section-heading products-heading">
                <div>
                    <p class="eyebrow">NEW IN THE CAVE</p>
                    <h2>Meet your next<br><em>favourite.</em></h2>
                </div>

                <a href="shop.php" class="button button-outline-dark">
                    Shop all products <span>↗</span>
                </a>
            </div>

            <?php if (count($products) > 0): ?>
            <div class="product-grid">
                <?php foreach ($products as $index => $product): ?>
                <?php
                    $imageName = trim((string) ($product['image'] ?? ''));
                    $imageUrl = '../admin/uploads/products/' .
                        rawurlencode(basename($imageName));
                    $inStock = (int) $product['stock'] > 0;
                    ?>

                <article class="product-card">
                    <a class="product-image" href="product.php?id=<?= (int) $product['id'] ?>">

                        <?php if ($imageName !== ''): ?>
                        <img src="<?= homeEscape($imageUrl) ?>" alt="<?= homeEscape($product['name']) ?>" loading="lazy"
                            onerror="this.style.display='none';">
                        <?php else: ?>
                        <div class="product-no-image">
                            <span>NEEMS</span>
                        </div>
                        <?php endif; ?>

                        <span class="product-index">
                            <?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?>
                        </span>

                        <?php if (!$inStock): ?>
                        <span class="product-status">SOLD OUT</span>
                        <?php else: ?>
                        <span class="product-view">Discover piece ↗</span>
                        <?php endif; ?>
                    </a>

                    <div class="product-details">
                        <div class="product-meta">
                            <span><?= homeEscape($product['category'] ?: 'THE COLLECTION') ?></span>
                            <span><?= $inStock ? 'AVAILABLE' : 'SOLD OUT' ?></span>
                        </div>

                        <h3>
                            <a href="product.php?id=<?= (int) $product['id'] ?>">
                                <?= homeEscape($product['name']) ?>
                            </a>
                        </h3>

                        <div class="product-price-row">
                            <span class="product-price">
                                <?= homeMoney($product['price']) ?>
                            </span>

                            <a href="product.php?id=<?= (int) $product['id'] ?>" class="product-arrow"
                                aria-label="View product">
                                ↗
                            </a>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="products-empty">
                <span class="empty-symbol">N.</span>
                <p class="eyebrow">A LITTLE SOMETHING IS COMING</p>
                <h3>Your next favourite awaits.</h3>
                <p>New products will appear here when they're added and activated in the admin dashboard.</p>
                <a href="shop.php" class="button button-dark">Explore the shop ↗</a>
            </div>
            <?php endif; ?>

            <div class="products-bottom">
                <span>GOOD STYLE. GOOD FEELING.</span>
                <a href="shop.php">DISCOVER EVERYTHING <span>↗</span></a>
            </div>
        </section>

        <!-- Editorial story -->
        <section class="editorial-section">
            <div class="editorial-photo">
                <img src="images/about-fashion.jpg" alt="Fashion inspiration from Neems Fashion Cave" loading="lazy"
                    onerror="this.style.display='none';">
                <span class="editorial-photo-caption">THE ART OF BEING YOU</span>
            </div>

            <div class="editorial-copy">
                <div class="section-topline">
                    <span>03 / OUR PHILOSOPHY</span>
                </div>

                <p class="eyebrow">MORE THAN WHAT YOU WEAR</p>

                <h2>Let your style<br>tell your <em>story.</em></h2>

                <div class="editorial-rule"></div>

                <p>
                    At Neems Fashion Cave, we believe that getting dressed
                    should feel like coming into your own. It's about choosing
                    the colours, textures and silhouettes that reflect who
                    you are.
                </p>

                <p>
                    We're here to help you discover those pieces — for the
                    ordinary days, the big moments and everything in between.
                </p>

                <a href="about.php" class="editorial-link">
                    A LITTLE MORE ABOUT US <span>↗</span>
                </a>

                <span class="editorial-watermark">NEEMS</span>
            </div>
        </section>

        <!-- Shop callout -->
        <section class="closing-section">
            <div class="closing-inner">
                <p class="eyebrow">YOUR NEXT LOOK IS WAITING</p>
                <h2>Dress like<br><em>you mean it.</em></h2>
                <p>Find the pieces that make getting dressed feel special.</p>
                <a href="shop.php" class="button button-light">
                    Find your style <span>↗</span>
                </a>
            </div>
            <span class="closing-decoration">N.</span>
        </section>

    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-main">
            <div class="footer-brand">
                <a href="index.php" class="brand footer-logo">
                    <span class="brand-symbol">N.</span>
                    <span class="brand-copy">
                        <strong>NEEMS</strong>
                        <small>FASHION CAVE</small>
                    </span>
                </a>

                <p>
                    A little style, a lot of personality.
                    Discover fashion that feels like you.
                </p>

                <a href="shop.php" class="footer-shop-link">
                    FIND YOUR STYLE ↗
                </a>
            </div>

            <div class="footer-column">
                <p class="footer-heading">EXPLORE</p>
                <a href="index.php">Home</a>
                <a href="shop.php">Shop all products</a>
                <a href="shop.php?category=Women">Women</a>
                <a href="shop.php?category=Men">Men</a>
                <a href="shop.php?category=Kids">Kids</a>
                <a href="shop.php?category=Shoes">Shoes</a>
            </div>

            <div class="footer-column">
                <p class="footer-heading">YOUR ACCOUNT</p>
                <a href="login.php">Sign in</a>
                <a href="account.php">My account</a>
                <a href="cart.php">Shopping cart</a>
                <a href="orders.php">My orders</a>
                <a href="about.php">Our story</a>
                <a href="contact.php">Contact us</a>
            </div>

            <div class="footer-column footer-contact">
                <p class="footer-heading">SAY HELLO</p>
                <p>No 15, Ado Muazu Street,<br>Durumi, Area 1, Abuja.</p>
                <a href="mailto:neemsfashioncave@gmail.com">
                    neemsfashioncave@gmail.com
                </a>
                <a href="tel:+2348069103798">+234 806 910 3798</a>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© <?= date('Y') ?> Neems Fashion Cave. All rights reserved.</p>
            <p>MADE WITH STYLE IN ABUJA, NIGERIA</p>
        </div>
    </footer>

</body>

</html>