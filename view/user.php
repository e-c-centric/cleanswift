<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Login/login_register.php');
    exit();
}

$user_name = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../css/user.css">
    <script src="js/dashboard.js" defer></script>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <h2>CleanSwift</h2>
            </div>
            <nav class="nav">
                <ul>
                <li><a href="../view/product_listing.php">Shop</a></li>
                    <li><a href="../view/cart.php">Cart</a></li>
                    <li><a href="checkout.php">Check Out</a></li>
                    <li><a href="settings.php">Settings</a></li>
                    <li><a href="../Login/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
                <h1>Laundry Dashboard</h1>
                <div class="user-profile">
                    <button class="user-profile-button">Welcome, <?php echo htmlspecialchars($user_name); ?></button>
                </div>
            </header>

            <section class="items-section">
                <h2>Available Items</h2>
                <div class="item-list">
                    <!-- Example item cards -->
                    <div class="item-card">
                        <img src="images/item1.jpg" alt="Item 1">
                        <h3>Eco-Friendly Detergent</h3>
                        <p>$24</p>
                        <button class="add-to-cart">Add to Cart</button>
                    </div>

                    <div class="item-card">
                        <img src="images/item2.jpg" alt="Item 2">
                        <h3>Laundry Basket</h3>
                        <p>$9</p>
                        <button class="add-to-cart">Add to Cart</button>
                    </div>

                    <div class="item-card">
                        <img src="images/item3.jpg" alt="Item 3">
                        <h3>Reusable Laundry Bag</h3>
                        <p>$13</p>
                        <button class="add-to-cart">Add to Cart</button>
                    </div>

                    <!-- Add more items as needed -->
                </div>
            </section>
        </main>
    </div>
</body>
</html>
