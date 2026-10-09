<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nees fashion cave</title>
    <link rel="stylesheet" href="public/css/header.css">
    <link rel="stylesheet" href="public/css/footer.css">
</head>

<body>
    <nav>
        <div class="nav-inner">
            <a href="#" class="logo"><img style="width: 180px; height: 90px;"
                    src="public/icons/Gemini_Generated_Image_281v4q281v4q281v-removebg-preview.png" alt=""></a>
            <ul class="nav-links">
                <li><a onclick="filterProducts('all')" class="active" id="nav-all">All</a></li>
                <li><a onclick="filterProducts('men')" id="nav-men">Men</a></li>
                <li><a onclick="filterProducts('women')" id="nav-women">Women</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <button class="cart-btn" onclick="toggleCart()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <path d="M16 10a4 4 0 0 1-8 0" />
                </svg>
                Cart
                <span class="cart-count" id="cartCount" style="display:none">0</span>
            </button>
            <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>