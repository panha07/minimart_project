<?php

$product_name = $category_id = $brand_id = $img = $description = $features = $specifications = $price =$qty="";

$sql = "SELECT category_id,category_name FROM categories where status='active'";

$category_result = mysqli_query($conn, $sql);
$brand_sql = "SELECT brand_id,brand_name FROM brands";
$brand_result = mysqli_query($conn, $brand_sql);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $brand_id = mysqli_real_escape_string($conn, $_POST['brand_id']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $base_price = mysqli_real_escape_string($conn, $_POST['base_price']);
    $qty = mysqli_real_escape_string($conn, $_POST['quantity']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $errorMessages = [];
    $Messages = [];

    if (empty($product_name)) {
        $errorMessages[] = "ឈ្មោះទំនិញមិនអាចទទេ"; // "Product name cannot be empty"
    } elseif (!preg_match('/^[\p{L}\x{1780}-\x{17FF}\s-]+$/u', $product_name)) {
        $errorMessages[] = "ឈ្មោះទំនិញមិនត្រឹមត្រូវ🥹"; // "Product name is incorrect"
    }
    

    if (empty($category_id)) {
        $errorMessages[] = "សូមជ្រើសរើសប្រភេទទំនិញ";
    }

    if (empty($brand_id)) {
        $errorMessages[] = "សូមជ្រើសរើសមាក់🥹";
    }

    if (!preg_match('/^[0-9]+(\.[0-9]{1,2})?$/', $price) || $price <= 0) {
        $errorMessages[] = "តម្លៃត្រូវតែវិជ្ជមាន (ធំជាងសូន្យ)";
    }
    if (!preg_match('/^[0-9]+(\.[0-9]{1,2})?$/', $base_price) || $base_price <= 0) {
        $errorMessages[] = "តម្លៃគោលត្រូវតែវិជ្ជមាន (ធំជាងសូន្យ)";
    }
    if (!preg_match('/^[0-9]+(\.[0-9]{1,2})?$/', $qty) || $qty < 0) {
        $errorMessages[] = "qty ត្រូវតែវិជ្ជមាន (ធំជាងសូន្យ)";
    }
    if ($base_price < $price) {
        $errorMessages[] = "Base Price ត្រូវធំជាង ឬ ស្មើរតម្លៃលក់ 🥹";
    }
    // Handle file upload for image
    if (isset($_FILES['img']['name']) && $_FILES['img']['name'] != "") {
        $target_dir = "../database/img/product/";
        $img = basename($_FILES["img"]["name"]);
        $target_file = $target_dir . $img;

   
        $check = getimagesize($_FILES["img"]["tmp_name"]);
        if ($check === false) {
            $errorMessages[] = "File នេះមិនមែនជា file រូបភាព!🥹";
        }

        if (empty($errorMessages)) {
            move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
        }
    } else {
        $errorMessages[] = "សូមជ្រើសរើសរូបភាពទំនិញ🥹";
    }

    if (!empty($errorMessages)) {
        foreach ($errorMessages as $msg) {
            $messages[] = ['text' => $msg, 'type' => 'danger'];
        }
    } else {
            $sql = "INSERT INTO products (name, category_id, img, description, price, base_price, brand_id, status)
                    VALUES ('$product_name', '$category_id', '$img', '$description', '$price', '$base_price', '$brand_id', '$status')";
    
            if (mysqli_query($conn, $sql)) {
                $last_id = mysqli_insert_id($conn);
                $inventory_sql = "INSERT INTO inventory (product_id, quantity) VALUES ('$last_id', '$qty')";
    
                if (mysqli_query($conn, $inventory_sql)) {
                    $messages[] = ['text' => "ទំនិញត្រូវបានបន្ថែម🧙", 'type' => 'success'];
                } else {
                    $messages[] = ['text' => "Error inserting into inventory: " . mysqli_error($conn), 'type' => 'danger'];
                }
            } else {
                $messages[] = ['text' => "Error inserting product: " . mysqli_error($conn), 'type' => 'danger'];
            }
        }
    }
?>

<div class="container shadow-sm p-3  mt-5 bg-body-tertiary rounded">
    <div class="container  p-4 ">
        <a class="nav-link" href="index.php?pg=product">
            <i class="fas fa-chevron-left"></i>
            <span>ទំនិញ</span>
        </a>

        <h2 class="text-center mb-5">បន្ថែមទំនិញ</h2>
        <hr class="sidebar-divider">

        <?php require_once('page/message.php') ?>

        <form action="index.php?pg=add_product" method="POST" enctype="multipart/form-data">
            <div id="message" class="alert" style="display: none;"></div>

            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="product_name">ឈ្មោះទំនិញ</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="price">តម្លៃ (USD)</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="base_price">Base Price (USD)</label>
                        <input type="number" step="0.01" class="form-control" id="base_price" name="base_price"
                            required>
                    </div>
                </div>
            </div>





            <div class="form-row">
            <div class="col-md-3">
                    <div class="form-group">
                        <label for="quantity">Qty</label>
                        <input type="number" step="0.01" class="form-control" id="quantity" name="quantity"
                            required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select class="form-control" id="category_id" name="category_id" required>

                            <?php while ($row = mysqli_fetch_assoc($category_result)): ?>
                            <option value="<?= $row['category_id'] ?>"><?= $row['category_name'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                
                    <div class="form-group">
                        <label for="brand_id">Brand</label>
                        <select class="form-control" id="brand_id" name="brand_id" required>

                            <?php while ($row = mysqli_fetch_assoc($brand_result)): ?>
                            <option value="<?= $row['brand_id'] ?>"><?= $row['brand_name'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <?php $status = isset($status) ? $status : 'available';?>
                    <div class="form-group">
                        <label for="status">Status</label><br>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="available" value="available"
                                <?php if ($status == 'available') echo 'checked'; ?>>
                            <label class="form-check-label" for="available">Available</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="unavailable"
                                value="unavailable" <?php if ($status == 'unavailable') echo 'checked'; ?>>
                            <label class="form-check-label" for="unavailable">Unavailable</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="pending" value="pending"
                                <?php if ($status == 'pending') echo 'checked'; ?>>
                            <label class="form-check-label" for="pending">Pending</label>
                        </div>
                    </div>

                </div>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="img">រូបភាពទំនិញ</label>
                <input type="file" class="form-control-file" id="img" name="img" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">បន្ថែមទំនិញ</button>
        </form>
    </div>
</div>