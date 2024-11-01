<?php
// Assuming you have a database connection already established as $conn
$brand_name = $create_date = $update_date = "";
$errorMessages = [];
$messages = [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brand_name = mysqli_real_escape_string($conn, $_POST['brand_name']);
    $create_date = date('Y-m-d H:i:s'); // Set the current date for create_date
    $update_date = date('Y-m-d H:i:s'); // Set the same date for update_date initially
    
    // Validate input fields
    if (empty($brand_name)) {
        $errorMessages[] = "ឈ្មោះ brand មិនអាចទទេ";
    }

    // Check for errors
    if (!empty($errorMessages)) {
        foreach ($errorMessages as $msg) {
            $messages[] = ['text' => $msg, 'type' => 'danger'];
        }
    } else {
        // Insert the new brand into the database
        $sql = "INSERT INTO brands (brand_name, create_date, update_date) 
                VALUES ('$brand_name', '$create_date', '$update_date')";
        
        if (mysqli_query($conn, $sql)) {
            $messages[] = ['text' => "Brand ត្រូវបានបន្ថែម 🧙", 'type' => 'success'];
        } else {
            $messages[] = ['text' => "Error: " . mysqli_error($conn), 'type' => 'danger'];
        }
    }
}
?>

<div class="container shadow-sm p-3 mt-5 bg-body-tertiary rounded">
    <div class="container p-4">
        <a class="nav-link" href="index.php?pg=brand">
            <i class="fas fa-chevron-left"></i>
            <span>Brands</span>
        </a>

        <h2 class="text-center mb-5">បន្ថែម Brand</h2>
        <hr class="sidebar-divider">

        <?php require_once('page/message.php') ?>

        <!-- Brand Form -->
        <form action="index.php?pg=add_brand" method="POST">
            <div id="message" class="alert" style="display: none;"></div>

            <div class="form-group mb-3">
                <label for="brand_name">ឈ្មោះ Brand</label>
                <input type="text" class="form-control" id="brand_name" name="brand_name" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary btn-block">បន្ថែម Brand</button>
        </form>
    </div>
</div>
