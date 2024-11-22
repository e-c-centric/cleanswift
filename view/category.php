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
    <title>Add Category</title>
    <link rel="stylesheet" href="../css/category.css">
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
                <h1>Add Category</h1>
                <div class="user-profile">
                    <button class="user-profile-button">Welcome, <?php echo htmlspecialchars($user_name); ?></button>
                </div>
            </header>

            <div>
                <!-- Add Category Form -->
               
                <form id="categoryForm" action="../actions/add_category_action.php" method="POST">
                    <div class="form-group">
                        <label for="name">Category Name:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <button type="submit">Add Category</button>
                </form>

                <!-- Delete Category Form -->

                <form id="deleteCategoryForm" action="../actions/delete_category_action.php" method="POST">
                    <div class="form-group">
                        <label for="cat_id">Select Category to Delete:</label>
                        <select name="cat_id" id="cat_id" required>
                            <?php
                            // Get the list of categories for the dropdown
                            include("../controllers/category_controller.php");
                            $categories = viewAllCategories_ctr();
                            foreach ($categories as $category) {
                                echo "<option value='" . $category['cat_id'] . "'>" . $category['cat_name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="delete-button">Delete Category</button>
                </form>

                <!-- List of Categories -->
                <h2>List of Categories</h2>
                <ul id="categoryList">
                    <?php
                    // Loop through and display all categories
                    if ($categories) {
                        foreach ($categories as $category) {
                            echo "<li>" . htmlspecialchars($category['cat_name']) . "</li>";
                        }
                    } else {
                        echo "<li>No categories added yet.</li>";
                    }
                    ?>
                </ul>
            </div>
        </main>
    </div>
    
    <script src="../js/category.js"></script>
</body>

</html>