<?php
session_start();
require_once ("../controllers/cart_controller.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Login/login_register.php');
    exit();
}

$user_name = $_SESSION['user_name'];

// Fetch cart items for the logged-in user
$c_id = $_SESSION['user_id'];
$cartItems = viewCartController($c_id);

// Calculate total cost
$totalPrice = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/cart.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>

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
                <h1>Your Cart</h1>
                <div class="user-profile">
                    <button class="user-profile-button">Welcome, <?php echo htmlspecialchars($user_name); ?></button>
                </div>
            </header>

            
            <div class="cart-section">
                <?php if ($cartItems): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $item): ?>
                                <?php
                                // Calculate price for each item
                                $itemTotal = $item['product_price'] * $item['qty'];
                                $totalPrice += $itemTotal;
                                ?>
                                <tr>
                                    <td><img src="../images/<?php echo htmlspecialchars($item['product_image']); ?>" alt="<?php echo htmlspecialchars($item['product_title']); ?>" class="img-thumbnail" width="100"></td>
                                    <td><?php echo htmlspecialchars($item['product_title']); ?></td>
                                    <td>$<?php echo htmlspecialchars($item['product_price']); ?></td>
                                    <td>
                                        <form action="../actions/update_cart_action.php" method="POST" class="d-inline">
                                            <input type="hidden" name="product_id" value="<?php echo $item['p_id']; ?>">
                                            <input type="hidden" name="customer_id" value="<?php echo $c_id; ?>">
                                            <input type="number" name="quantity" value="<?php echo $item['qty']; ?>" min="1" class="form-control" style="width: 70px;">
                                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                        </form>
                                    </td>
                                    <td>$<?php echo number_format($itemTotal, 2); ?></td>
                                    <td>
                                        <form action="../actions/delete_cart_item_action.php" method="POST">
                                            <input type="hidden" name="product_id" value="<?php echo $item['p_id']; ?>">
                                            <input type="hidden" name="customer_id" value="<?php echo $c_id; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="total">
                        <h3>Total Price: $<?php echo number_format($totalPrice, 2); ?></h3>
                        <a href="#" class="btn btn-success">Proceed to Checkout</a>
                    </div>
                <?php else: ?>
                    <p>Your cart is empty.</p>
                    <a href="product_listing.php" class="btn btn-primary">Continue Shopping</a>
                <?php endif; ?>
            </div>
        </main>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
