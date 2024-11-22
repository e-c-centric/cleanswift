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
    <title>Add Brand</title>
    <link rel="stylesheet" href="../css/brand.css">
    <link rel="stylesheet" href="../css/dashboard.css">
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
                <h1>Add Brand</h1>
                <div class="user-profile">
                    <button class="user-profile-button">Welcome, <?php echo htmlspecialchars($user_name); ?></button>
                </div>
            </header>

            <div>
                <!-- Add Brand Form -->

                <form id="brandForm" action="../actions/add_brand_action.php" method="POST">
                    <div class="form-group">
                        <label for="name">Brand Name:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <button type="submit">Add Brand</button>
                </form>

                <!-- Delete Brand Form -->
                <form id="deleteBrandForm" action="../actions/delete_brand_action.php" method="POST">
                    <div class="form-group">
                        <label for="brand_id">Select Brand to Delete:</label>
                        <select name="brand_id" id="brand_id" required>
                            <?php
                            // Get the list of brands for the dropdown
                            include("../controllers/brand_controller.php");
                            $brands = get_all_brands_ctr();
                            foreach ($brands as $brand) {
                                echo "<option value='" . $brand['brand_id'] . "'>" . $brand['brand_name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="delete-button">Delete Brand</button>
                </form>

                <!-- List of Brands -->
                <h2>List of Brands</h2>
                <ul id="brandList">
                    <?php
                    // Loop through and display all brands
                    if ($brands) {
                        foreach ($brands as $brand) {
                            echo "<li>" . htmlspecialchars($brand['brand_name']) . "</li>";
                        }
                    } else {
                        echo "<li>No brands added yet.</li>";
                    }
                    ?>
                </ul>
            </div>
        </main>
    </div>
</body>
</html>