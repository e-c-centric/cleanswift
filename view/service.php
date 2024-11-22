<?php
session_start();

include_once("../settings/db_class.php");
include_once("../controllers/category_controller.php");
include_once("../controllers/brand_controller.php");
include_once("../controllers/product_controller.php");

$categories = viewAllCategories_ctr();
$brands = get_all_brands_ctr();
$products = view_product_ctr();

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
    <title>Add Service</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../css/service.css">
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <h2>CleanSwift</h2>
            </div>
            <nav class="nav">
                <ul>
                <li><a href="../view/dashboard.php">Dashboard</a></li>
                    <li><a href="../view/service.php">Add service</a></li>
                    <li><a href="../view/brand.php">Add brand</a></li>
                    <li><a href="../view/category.php">Add category</a></li>
                    <li><a href="#">Orders</a></li>
                    <li><a href="#">Earnings</a></li>
                    <li><a href="#">Laundry Providers</a></li>
                     <li><a href="#">Laundry Drivers</a></li>
                    <li><a href="#">Customer Details</a></li>
                    <li><a href="#">Settings</a></li>
                    <li><a href="../Login/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header class="main-header">
            <h1>Add a Service</h1>
                <div class="user-profile">
                    <button class="user-profile-button">Welcome, <?php echo htmlspecialchars($user_name); ?></button>
                </div>
            </header>

            <h2> </h2>
            <form id="serviceForm" action="../actions/add_product_action.php" method="POST">
                <div class="form-group">
                    <label for="category">Select Category:</label>
                    <select name="category" id="category" required>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['cat_id']; ?>">
                                <?php echo htmlspecialchars($category['cat_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="brand">Select Brand:</label>
                    <select name="brand" id="brand" required>
                        <?php foreach ($brands as $brand): ?>
                            <option value="<?php echo $brand['brand_id']; ?>">
                                <?php echo htmlspecialchars($brand['brand_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" required>
                </div>

                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" required>
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required></textarea>
                </div>

                <div class="form-group">
                    <label for="keywords">Keywords:</label>
                    <input type="text" id="keywords" name="keywords" required>
                </div>

                <button type="submit">Add Service</button>
            </form>

            <h2>Service List</h2>
            <ul id="serviceList">
                <?php foreach ($products as $product): ?>
                    <li>
                        <div>
                            <strong><?php echo htmlspecialchars($product['product_title']); ?></strong><br>
                            <span>Price: <?php echo htmlspecialchars($product['product_price']); ?></span><br>
                            <span>Description: <?php echo htmlspecialchars($product['product_desc']); ?></span><br>
                            <span>Keywords: <?php echo htmlspecialchars($product['product_keywords']); ?></span>
                        </div>
                        <form class="deleteForm" action="../actions/delete_product_action.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <button type="submit">Delete</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </main>
    </div>

    <script src="../js/service.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
