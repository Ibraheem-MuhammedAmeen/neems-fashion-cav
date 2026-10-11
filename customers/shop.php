<?php
session_start();

require_once __DIR__ . '/../includes/config.php';

/*
|--------------------------------------------------------------------------
| Search, category and sorting
|--------------------------------------------------------------------------
*/

$search = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');
$sort = trim($_GET['sort'] ?? 'newest');

$allowedCategories = [
    'Men',
    'Women',
    'Kids',
    'Shoes',
    'Accessories'
];

if (!in_array($category, $allowedCategories, true)) {
    $category = '';
}

$sortOptions = [
    'newest'     => 'id DESC',
    'low_price'  => 'price ASC',
    'high_price' => 'price DESC',
    'name'       => 'name ASC'
];

$orderBy = $sortOptions[$sort] ?? 'id DESC';

/*
|--------------------------------------------------------------------------
| Build the products query
|--------------------------------------------------------------------------
*/

$sql = "SELECT id, name, description, price, category,
               size, color, stock, image
        FROM products
        WHERE status = 'active'";

$conditions = [];
$params = [];
$types = '';

if ($search !== '') {
    $conditions[] = "(name LIKE ? OR description LIKE ?)";
    $searchTerm = '%' . $search . '%';

    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= 'ss';
}

if ($category !== '') {
    $conditions[] = "category = ?";
    $params[] = $category;
    $types .= 's';
}

if (!empty($conditions)) {
    $sql .= ' AND ' . implode(' AND ', $conditions);
}

$sql .= " ORDER BY $orderBy";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die('Unable to load products. Please try again later.');
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

$stmt->close();

function shopEscape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function shopMoney($amount)
{
    return '₦' . number_format((float) $amount, 2);
}

/*
|--------------------------------------------------------------------------
| Cart count
|--------------------------------------------------------------------------
| Supports a session cart where each item has a quantity.
*/

$cartCount = 0;
$cart = $_SESSION['cart'] ?? [];

if (is_array($cart)) {
    foreach ($cart as $item) {
        if (is_array($item)) {
            $cartCount += (int) ($item['quantity'] ?? 0);
        } else {
            $cartCount += (int) $item;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Shop | Neems Fashion Cave</title>

    <link rel="stylesheet" href="css/shop.css">
</head>

<body>

    <!-- ================= NAVBAR ================= -->

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
                    <span class="cart-icon">🛍</span>
                    Cart
                    <span class="cart-count">
                        <?= $cartCount ?>
                    </span>
                </a>

                <a href="login.php" class="account-link">Account</a>
            </div>

        </div>
    </header>


    <!-- ================= PAGE HERO ================= -->

    <section class="shop-hero">
        <div class="shop-hero-content">
            <p class="eyebrow">THE NEEMS COLLECTION</p>

            <h1>Find Your <span>Signature Style.</span></h1>

            <p>
                Explore thoughtfully selected fashion for every mood,
                every occasion, and every version of you.
            </p>

            <a href="#products" class="hero-button">
                Explore Collection
                <span>→</span>
            </a>
        </div>
    </section>


    <!-- ================= SHOP CONTENT ================= -->

    <main class="shop-container" id="products">

        <div class="shop-heading">
            <div>
                <p class="eyebrow">CURATED FOR YOU</p>
                <h2>Shop Our Collection</h2>
                <p class="section-description">
                    Discover pieces that bring your personal style to life.
                </p>
            </div>

            <p class="product-count">
                <?= count($products) ?>
                <?= count($products) === 1 ? 'product' : 'products' ?> found
            </p>
        </div>


        <!-- SEARCH AND SORT -->

        <form action="shop.php" method="GET" class="shop-toolbar">

            <div class="search-box">
                <span class="search-icon">⌕</span>

                <input type="search" name="search" placeholder="Search clothes, shoes and more..."
                    value="<?= shopEscape($search) ?>">

                <?php if ($category !== ''): ?>
                <input type="hidden" name="category" value="<?= shopEscape($category) ?>">
                <?php endif; ?>

                <button type="submit">Search</button>
            </div>

            <div class="sort-box">
                <label for="sort">Sort by</label>

                <select name="sort" id="sort" onchange="this.form.submit()">
                    <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>
                        Newest
                    </option>

                    <option value="low_price" <?= $sort === 'low_price' ? 'selected' : '' ?>>
                        Price: Low to High
                    </option>

                    <option value="high_price" <?= $sort === 'high_price' ? 'selected' : '' ?>>
                        Price: High to Low
                    </option>

                    <option value="name" <?= $sort === 'name' ? 'selected' : '' ?>>
                        Name: A to Z
                    </option>
                </select>
            </div>

        </form>


        <!-- CATEGORY FILTERS -->

        <div class="category-filters">

            <a href="shop.php<?= $search !== '' ? '?search=' . urlencode($search) : '' ?>"
                class="category-chip <?= $category === '' ? 'selected' : '' ?>">
                All Items
            </a>

            <?php foreach ($allowedCategories as $cat): ?>
            <?php
                $query = [
                    'category' => $cat,
                    'sort' => $sort
                ];

                if ($search !== '') {
                    $query['search'] = $search;
                }
            ?>

            <a href="shop.php?<?= shopEscape(http_build_query($query)) ?>"
                class="category-chip <?= $category === $cat ? 'selected' : '' ?>">
                <?= shopEscape($cat) ?>
            </a>
            <?php endforeach; ?>

        </div>


        <!-- PRODUCT GRID -->

        <?php if (count($products) > 0): ?>

        <div class="product-grid">

            <?php foreach ($products as $product): ?>

            <?php
                    $imageName = basename((string) ($product['image'] ?? ''));

                    $imagePath = '../admin/uploads/products/' .
                        rawurlencode($imageName);

                    $hasImage = $imageName !== '';
                    $inStock = (int) $product['stock'] > 0;
                ?>

            <article class="product-card">

                <a href="product.php?id=<?= (int) $product['id'] ?>" class="product-image-link">

                    <div class="product-image">

                        <?php if ($hasImage): ?>

                        <img src="<?= shopEscape($imagePath) ?>" alt="<?= shopEscape($product['name']) ?>"
                            loading="lazy"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                        <div class="image-placeholder" style="display:none;">
                            <span>👗</span>
                            <small>Neems Fashion Cave</small>
                        </div>

                        <?php else: ?>

                        <div class="image-placeholder">
                            <span>👗</span>
                            <small>Neems Fashion Cave</small>
                        </div>

                        <?php endif; ?>

                        <?php if (!$inStock): ?>
                        <span class="stock-badge out-of-stock">
                            Sold Out
                        </span>
                        <?php else: ?>
                        <span class="stock-badge">
                            In Stock
                        </span>
                        <?php endif; ?>

                    </div>

                </a>

                <div class="product-info">

                    <p class="product-category">
                        <?= shopEscape($product['category'] ?: 'Collection') ?>
                    </p>

                    <h3>
                        <a href="product.php?id=<?= (int) $product['id'] ?>">
                            <?= shopEscape($product['name']) ?>
                        </a>
                    </h3>

                    <?php if (!empty($product['description'])): ?>
                    <p class="product-description">
                        <?= shopEscape($product['description']) ?>
                    </p>
                    <?php endif; ?>

                    <div class="product-bottom">

                        <span class="product-price">
                            <?= shopMoney($product['price']) ?>
                        </span>

                        <a href="product.php?id=<?= (int) $product['id'] ?>" class="view-product"
                            aria-label="View <?= shopEscape($product['name']) ?>">
                            View →
                        </a>

                    </div>

                    <?php if (!empty($product['size']) || !empty($product['color'])): ?>
                    <div class="product-options">

                        <?php if (!empty($product['size'])): ?>
                        <span>
                            Size: <?= shopEscape($product['size']) ?>
                        </span>
                        <?php endif; ?>

                        <?php if (!empty($product['color'])): ?>
                        <span>
                            Color: <?= shopEscape($product['color']) ?>
                        </span>
                        <?php endif; ?>

                    </div>
                    <?php endif; ?>

                </div>

            </article>

            <?php endforeach; ?>

        </div>

        <?php else: ?>

        <div class="empty-state">
            <div class="empty-icon">⌕</div>

            <h3>No products found</h3>

            <p>
                Try a different search term or choose another category.
            </p>

            <a href="shop.php" class="clear-filters">
                View All Products
            </a>
        </div>

        <?php endif; ?>

    </main>


    <!-- ================= BENEFITS ================= -->

    <section class="benefits">
        <div class="benefit">
            <span class="benefit-icon">♧</span>
            <div>
                <h3>Quality Fashion</h3>
                <p>Styles selected with care.</p>
            </div>
        </div>

        <div class="benefit">
            <span class="benefit-icon">↗</span>
            <div>
                <h3>Easy Shopping</h3>
                <p>Find your next favourite look.</p>
            </div>
        </div>

        <div class="benefit">
            <span class="benefit-icon">♡</span>
            <div>
                <h3>Made for Your Style</h3>
                <p>Fashion for every occasion.</p>
            </div>
        </div>
    </section>


    <!-- ================= FOOTER ================= -->

    <footer class="site-footer">

        <div class="footer-content">

            <div class="footer-brand">
                <a href="index.php" class="brand footer-logo">
                    <span class="brand-name">NEEMS</span>
                    <span class="brand-subtitle">FASHION CAVE</span>
                </a>

                <p>
                    Discover your style with thoughtfully selected fashion
                    made for everyday confidence.
                </p>
            </div>

            <div class="footer-column">
                <h4>Shop</h4>
                <a href="shop.php">All Products</a>
                <a href="shop.php?category=Men">Men</a>
                <a href="shop.php?category=Women">Women</a>
                <a href="shop.php?category=Shoes">Shoes</a>
            </div>

            <div class="footer-column">
                <h4>Information</h4>
                <a href="about.php">About Us</a>
                <a href="contact.php">Contact</a>
                <a href="account.php">My Account</a>
                <a href="orders.php">My Orders</a>
            </div>

            <div class="footer-column">
                <h4>Get in Touch</h4>
                <p>No 15, Ado Muazu Street, Durumi, Area 1, Abuja</p>
                <p>neemsfashioncave@gmail.com</p>
                <p>+234 806 910 3798</p>
            </div>

        </div>

        <div class="footer-bottom">
            <p>
                &copy; <?= date('Y') ?> Neems Fashion Cave.
                All rights reserved.
            </p>
        </div>

    </footer>

</body>

</html>