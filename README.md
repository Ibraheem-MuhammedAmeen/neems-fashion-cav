# Neems Fashion Cave 👗

**Neems Fashion Cave** is a fashion e-commerce web application built with PHP, MySQL, HTML, CSS, and JavaScript. It provides customers with an online shopping experience where they can explore fashion products, view product details, manage their shopping cart, and proceed to checkout.

The application also includes customer authentication and an administrative area for managing products.

## ✨ Features

### Customer Features
- **Homepage:** Browse featured and recently added products.
- **Shop Page:** Explore available fashion products.
- **Product Details:** View individual product information.
- **Shopping Cart:** Add products, update quantities, and remove items.
- **Checkout:** Proceed from the shopping cart to checkout.
- **Order Success:** View an order confirmation page.
- **Customer Registration:** Create a customer account.
- **Customer Login:** Sign in to an existing account.
- **Customer Account:** Access the customer account page.
- **Contact Page:** View contact information and submit an inquiry form.
- **About Page:** Learn more about Neems Fashion Cave.
- **Responsive Design:** Browse the website on desktop and mobile screens.

### Admin Features
- Manage fashion products through the administrative area.
- Upload product images.
- Store product information in the database.

*Note: The availability of individual administrative features depends on the admin pages implemented in the project.*

## 🛠️ Technologies Used

- **PHP** — Server-side application logic.
- **MySQL** — Database management.
- **HTML5** — Page structure.
- **CSS3** — Styling and responsive layouts.
- **JavaScript** — Client-side interactivity.
- **XAMPP** — Local development environment.
- **Git and GitHub** — Version control and source code hosting.

## 📁 Project Structure

```text
neems-fashion-cave/
├── admin/
│   └── uploads/
│       └── products/
├── includes/
│   └── config.php
├── customers/
│   ├── index.php
│   ├── about.php
│   ├── account.php
│   ├── cart.php
│   ├── checkout.php
│   ├── contact.php
│   ├── login.php
│   ├── logout.php
│   ├── order-success.php
│   ├── product.php
│   ├── register.php
│   ├── shop.php
│   ├── css/
│   │   ├── about.css
│   │   ├── cart.css
│   │   ├── contact.css
│   │   ├── order-success.css
│   │   ├── product.css
│   │   └── shop.css
│   ├── js/
│   │   └── home.js
│   └── images/
└── README.md
```

*This is a representative structure; additional files may exist in the complete project.*

## ⚙️ Installation and Setup

### 1. Install the Requirements

Install [XAMPP](https://www.apachefriends.org/) to get Apache, PHP, and MySQL.

You will also need a web browser and a code editor such as Visual Studio Code.

### 2. Clone the Repository

Open a terminal and run:

```bash
git clone YOUR_GITHUB_REPOSITORY_URL
```

Alternatively, download the repository as a ZIP file from GitHub and extract it into your XAMPP `htdocs` directory.

### 3. Move the Project

Place the project folder inside:

```text
C:\xampp\htdocs\
```

For example:

```text
C:\xampp\htdocs\neems fashion cav php\
```

### 4. Start Apache and MySQL

Open the XAMPP Control Panel and start:

- Apache
- MySQL

### 5. Create the Database

Open phpMyAdmin:

```text
http://localhost/phpmyadmin
```

Create a MySQL database for the application.

If your project includes an SQL export, import it into the database you created. The imported database should contain the tables required by the application.

### 6. Configure the Database Connection

Open:

```text
includes/config.php
```

Configure the connection using your local database details. For example:

```php
<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "YOUR_DATABASE_NAME";

$conn = new mysqli(
    $host,
    $username,
    $password,
    $database
);

if ($conn->connect_error) {
    die("Database connection failed.");
}

$conn->set_charset("utf8mb4");
```

Replace `YOUR_DATABASE_NAME` with the database name you created. Adjust the username and password if your MySQL configuration differs.

If your existing `config.php` already contains additional configuration, preserve the necessary settings rather than replacing the entire file blindly.

### 7. Run the Application

Open the application in your browser using the folder name:

```text
http://localhost/neems%20fashion%20cav%20php/customers/
```

If you rename the project folder to `neems-fashion-cave`, use:

```text
http://localhost/neems-fashion-cave/customers/
```

## 🛍️ Shopping Workflow

1. Open the homepage.
2. Browse available products or navigate to the shop.
3. Select a product to view its details.
4. Add the product to the shopping cart.
5. Review the cart and update quantities as needed.
6. Continue to checkout.
7. Complete the order process and view the confirmation page, if order processing is configured.

## 🗄️ Database

The application uses MySQL to store the data required by the shopping website.

Depending on the implemented schema, the database may include tables for:

- Products
- Customers
- Orders
- Order items
- Other application data

Import the project's SQL file, if provided, to create the required tables and relationships.

## 🔒 Security Considerations

For a production deployment:

- Store database credentials securely and never commit secrets to GitHub.
- Use password hashing and verification for customer authentication.
- Validate and sanitize user input.
- Use prepared statements for database queries.
- Verify product prices and stock against the database during checkout.
- Validate uploaded image types and sizes.
- Protect sensitive actions against unauthorized access and CSRF attacks.
- Use HTTPS when deploying the application.
- Disable detailed error output in production.

## 🚀 Deployment

The application can be deployed to a PHP-compatible web hosting service that supports MySQL.

Before deployment:

1. Upload the project files.
2. Create the production database.
3. Import the SQL database export.
4. Update the database configuration.
5. Configure writable directories for product image uploads where required.
6. Check file paths and image URLs.
7. Test customer registration, login, cart operations, and checkout.
8. Confirm that sensitive configuration files and credentials are not publicly exposed.

## 👨‍💻 Development

To contribute or continue development:

```bash
git add .
git commit -m "Describe your changes"
git push origin main
```

Replace the commit message with a short description of the changes you make.

## 📌 Project Status

Neems Fashion Cave is an ongoing PHP and MySQL e-commerce project focused on fashion product browsing, customer account functionality, and online shopping workflows.

## 📄 License

No license has been specified yet. All rights and reuse permissions remain subject to the repository owner's decision until a license is added.
