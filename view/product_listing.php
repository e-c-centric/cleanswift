<?php
session_start();
include_once("../settings/db_class.php");
include_once("../controllers/product_controller.php");

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Login/login_register.php');
    exit();
}
$user_name = $_SESSION['user_name'];
// Fetch all products
$products = view_product_ctr();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Listing</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/product_listing.css">
    
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
                <h1>Product Listing</h1>
                <div class="user-profile">
                    <button class="user-profile-button">Welcome, <?php echo htmlspecialchars($user_name); ?></button>
                </div>
            </header>

            <div class="container">
                <div class="product-grid">
                    <?php if ($products): ?>
                        <?php foreach ($products as $product): ?>
                            <div class="product-card">
                                <img src="../images/<?php echo htmlspecialchars($product['product_image']); ?>" alt="<?php echo htmlspecialchars($product['product_title']); ?>" class="product-image">
                                <h3><?php echo htmlspecialchars($product['product_title']); ?></h3>
                                <p><?php echo htmlspecialchars($product['product_desc']); ?></p>
                                <p><strong>Price:</strong> $<?php echo htmlspecialchars($product['product_price']); ?></p>
                                <form method="POST" action="../actions/add_to_cart_action.php">
                                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                    <input type="hidden" name="customer_id" value="<?php echo $_SESSION['user_id']; ?>"> <!-- Use session user ID -->
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No products available.</p>
                    <?php endif; ?>
                </div>

            </div>
        </main>
    </div>
</body>
</html>
