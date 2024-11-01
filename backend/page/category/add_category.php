<?php
$category_name=$description=$status="";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);


    $errorMessages = [];

    if (empty($category_name)) {
        $errorMessages[] = "ឈ្មោះប្រភេទទំនិញមិនអាចទទេ"; 
    } elseif (!preg_match('/^[\p{Khmer}\s]+$/u', $category_name)) {
        $errorMessages[] = "ឈ្មោះប្រភេទទំនិញមិនត្រឹមត្រូវ"; 
    }
    
    if (!empty($errorMessages)) {
        foreach ($errorMessages as $msg) {
            $messages[] = ['text' => $msg, 'type' => 'danger'];
        }
    } else {
        // Insert product into the database
        $sql = "INSERT INTO categories (category_name,description,status) VALUES('$category_name','$description','$status');";

        if (mysqli_query($conn, $sql)) {
            if (empty($messages)) {
                $messages[] = ['text' => "Category ត្រូវបានបន្ថែម🧙", 'type' => 'success'];
            }
         
        } else {
            $messages[] = ['text' => "Error: " . mysqli_error($conn), 'type' => 'danger'];
        }
    }
}
?>

<div class="container shadow-sm p-3 mb-5 mt-5 bg-body-tertiary rounded">
    <div class="container  p-4  mt-5 pb-3">
    <a class="nav-link" href="index.php?pg=category">
            <i class="fas fa-chevron-left"></i>
            <span>ប្រភេទទំនិញ</span>
        </a>
        <h2 class="text-center mb-5">បន្ថែមប្រភេទទំនិញ</h2>
        <hr class="sidebar-divider">

    <?php require_once('page/message.php') ?>

        <form action="index.php?pg=add_category" method="POST" enctype="multipart/form-data">
            <div id="message" class="alert" style="display: none;"></div>

            <div class="form-row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="category_name"><strong>ឈ្មោះប្រភេទទំនិញ</strong></label>
                        <input type="text" class="form-control" id="category_name" name="category_name" required>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="status"><strong>Status</strong></label><br>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="active" value="active"
                                <?php if (isset($status) || $status == 'active') echo 'checked'; ?>>
                            <label class="form-check-label" for="active">Active</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="inactive"
                                value="inactive"
                                <?php if (isset($status) && $status == 'inactive') echo 'checked'; ?>>
                            <label class="form-check-label" for="inactive">Inactive</label>
                        </div>
                    </div>

                </div>

            </div>

            <div class="form-group">
                <label for="description"><strong>បរិយាយ</strong></label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>





            <button type="submit" class="btn btn-primary btn-block">បន្ថែមទំនិញ</button>
        </form>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector('form');
        const messageDiv = document.getElementById('message');

        form.addEventListener('submit', function(event) {
            let isValid = true;
            messageDiv.style.display = "none"; 
            messageDiv.innerHTML = ''; 

     
       
            
            // Validate Category
            const categoryId = document.getElementById('category_id').value;
            if (categoryId === "") {
                messageDiv.innerHTML += "Please select a Category.<br>";
                isValid = false;
            }

            // Validate Brand
            const brandId = document.getElementById('brand_id').value;
            if (brandId === "") {
                messageDiv.innerHTML += "Please select a Brand.<br>";
                isValid = false;
            }

            // Validate Image Upload
            const img = document.getElementById('img').files[0];
            if (!img) {
                messageDiv.innerHTML += "Product Image is required.<br>";
                isValid = false;
            } else {
                const validImageTypes = ["image/jpeg", "image/png", "image/gif"];
                if (!validImageTypes.includes(img.type)) {
                    messageDiv.innerHTML +=
                        "Invalid image format. Only JPEG, PNG, and GIF are allowed.<br>";
                    isValid = false;
                }
            }

            // Validate Price (must be a positive number)
            const price = document.getElementById('price').value;
            const priceRegex = /^[0-9]+(\.[0-9]{1,2})?$/; // Numeric with optional decimal
            if (!priceRegex.test(price) || price <= 0) {
                messageDiv.innerHTML += "Price must be a positive number.<br>";
                isValid = false;
            }

            if (!isValid) {
                messageDiv.style.display = "block"; // Show message area
                event.preventDefault(); // Prevent form submission
            }
        });
    });
</script>