<?php
session_start();

require_once __DIR__ . '/../includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>About Us | Neems Fashion Cave</title>
    <meta name="description"
        content="Discover Neems Fashion Cave, a fashion destination for timeless style, confidence, and carefully selected looks.">

    <link rel="stylesheet" href="css/about.css?v=1">
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
            <a href="about.php" class="active">About</a>
            <a href="contact.php">Contact</a>
        </nav>

        <div class="header-actions">
            <a href="account.php">My Account</a>
            <a href="cart.php">Cart</a>
        </div>
    </header>

    <main>

        <!-- Hero section -->
        <section class="about-hero">
            <div class="hero-overlay"></div>

            <div class="hero-content">
                <p class="eyebrow">OUR STORY · OUR STYLE</p>

                <h1>Fashion is more than what you wear. <em>It's how you feel.</em></h1>

                <p>
                    Welcome to Neems Fashion Cave, where personal style meets
                    timeless elegance and every outfit is an opportunity to
                    express who you are.
                </p>

                <a href="shop.php" class="light-button">
                    Explore Our Collection <span>→</span>
                </a>
            </div>
        </section>

        <!-- Brand introduction -->
        <section class="story-section">
            <div class="story-image">
                <img src="images/about-fashion.jpg" alt="The fashion and style inspiration behind Neems Fashion Cave">
                <div class="image-caption">
                    <span>NEEMS</span>
                    <small>STYLE FOR EVERY YOU</small>
                </div>
            </div>

            <div class="story-content">
                <p class="eyebrow">WHO WE ARE</p>

                <h2>A little about <em>our world.</em></h2>

                <p>
                    At Neems Fashion Cave, we believe that fashion should be
                    exciting, expressive, and accessible. What you wear can
                    reflect your personality, celebrate your individuality,
                    and help you step into every day with confidence.
                </p>

                <p>
                    Our goal is to make discovering your next favourite look
                    simple and enjoyable. From everyday essentials to outfits
                    for special occasions, we bring together styles that help
                    you create a wardrobe that feels like you.
                </p>

                <p>
                    Whether you're refreshing your personal style, shopping
                    for someone special, or preparing for an important moment,
                    we're here to make the experience easier.
                </p>

                <a href="shop.php" class="text-link">
                    Discover Your Style <span>→</span>
                </a>
            </div>
        </section>

        <!-- Mission and vision -->
        <section class="mission-section">
            <div class="mission-heading">
                <p class="eyebrow">WHAT WE BELIEVE</p>
                <h2>Style with <em>purpose.</em></h2>
                <p>
                    Everything we do starts with the idea that fashion should
                    make you feel good about yourself.
                </p>
            </div>

            <div class="mission-grid">
                <article class="mission-card">
                    <div class="mission-number">01</div>
                    <h3>Our Mission</h3>
                    <p>
                        To make fashion shopping enjoyable by offering customers
                        a welcoming place to discover clothing, accessories,
                        and styles that suit their individual tastes.
                    </p>
                </article>

                <article class="mission-card featured">
                    <div class="mission-number">02</div>
                    <h3>Our Vision</h3>
                    <p>
                        To grow into a trusted fashion destination known for
                        inspiring personal expression, thoughtful selection,
                        and a memorable shopping experience.
                    </p>
                </article>

                <article class="mission-card">
                    <div class="mission-number">03</div>
                    <h3>Our Promise</h3>
                    <p>
                        To put the customer experience first, make browsing
                        simple, and help customers find pieces they can enjoy
                        wearing with confidence.
                    </p>
                </article>
            </div>
        </section>

        <!-- Fashion categories -->
        <section class="categories-section">
            <div class="section-heading">
                <p class="eyebrow">FIND YOUR LOOK</p>
                <h2>A style for <em>every you.</em></h2>
                <p>
                    Explore different looks, discover new favourites, and
                    make every outfit your own.
                </p>
            </div>

            <div class="category-grid">

                <a href="shop.php?category=Women" class="category-card">
                    <img src="images/women-fashion.jpg" alt="Women's fashion collection" loading="lazy">
                    <div class="category-overlay">
                        <span>01 / WOMEN</span>
                        <h3>Grace in Every Look</h3>
                        <span class="category-link">Explore Collection →</span>
                    </div>
                </a>

                <a href="shop.php?category=Men" class="category-card">
                    <img src="images/men-fashion.jpg" alt="Men's fashion collection" loading="lazy">
                    <div class="category-overlay">
                        <span>02 / MEN</span>
                        <h3>Everyday Confidence</h3>
                        <span class="category-link">Explore Collection →</span>
                    </div>
                </a>

                <a href="shop.php?category=Kids" class="category-card">
                    <img src="images/kids-fashion.jpg" alt="Children's fashion collection" loading="lazy">
                    <div class="category-overlay">
                        <span>03 / KIDS</span>
                        <h3>Little Trendsetters</h3>
                        <span class="category-link">Explore Collection →</span>
                    </div>
                </a>

                <a href="shop.php?category=Accessories" class="category-card">
                    <img src="images/accessories-fashion.jpg" alt="Fashion accessories collection" loading="lazy">
                    <div class="category-overlay">
                        <span>04 / ACCESSORIES</span>
                        <h3>The Finishing Touch</h3>
                        <span class="category-link">Explore Collection →</span>
                    </div>
                </a>

            </div>
        </section>

        <!-- Brand values -->
        <section class="values-section">
            <div class="section-heading">
                <p class="eyebrow">THE NEEMS EXPERIENCE</p>
                <h2>More than <em>just fashion.</em></h2>
            </div>

            <div class="values-grid">
                <article class="value-item">
                    <div class="value-icon">◇</div>
                    <h3>Personal Expression</h3>
                    <p>
                        Discover styles that let your personality shine.
                    </p>
                </article>

                <article class="value-item">
                    <div class="value-icon">♡</div>
                    <h3>Customer First</h3>
                    <p>
                        We aim to make your shopping journey smooth and welcoming.
                    </p>
                </article>

                <article class="value-item">
                    <div class="value-icon">✧</div>
                    <h3>Timeless Inspiration</h3>
                    <p>
                        Find outfit ideas for everyday moments and special occasions.
                    </p>
                </article>

                <article class="value-item">
                    <div class="value-icon">⌁</div>
                    <h3>Easy Shopping</h3>
                    <p>
                        Browse our collections and discover your next favourite look.
                    </p>
                </article>
            </div>
        </section>

        <!-- Call to action -->
        <section class="about-cta">
            <p class="eyebrow">YOUR STYLE JOURNEY STARTS HERE</p>

            <h2>Ready to find your <em>next favourite look?</em></h2>

            <p>
                Explore our collection and discover pieces that feel right for you.
            </p>

            <div class="cta-buttons">
                <a href="shop.php" class="dark-button">Shop the Collection →</a>
                <a href="contact.php" class="outline-button">Talk to Us</a>
            </div>
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