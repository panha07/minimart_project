<?php

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$product_name = $category_id = $brand_id = $img = $description = $features = $specifications = $price = $base_price = $status = $qty="";


if ($product_id > 0) {
    $sql = "SELECT * FROM products 
    inner join inventory on products.product_id=inventory.product_id
    WHERE products.product_id = '$product_id'";
    $product_result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($product_result) > 0) {
        $product = mysqli_fetch_assoc($product_result);
        $product_name = $product['name'];
        $category_id = $product['category_id'];
        $brand_id = $product['brand_id'];
        $img = $product['img'];
        $description = $product['description'];
        $price = $product['price'];
        $base_price = $product['base_price'];
        $qty = $product['quantity'];
        $status = $product['status'];
    } else {
        $messages[] = ['text' => "Product ពុំមាន!", 'type' => 'danger'];
    }
}

$sql = "SELECT category_id,category_name FROM categories WHERE status='active'";
$category_result = mysqli_query($conn, $sql);


$brand_sql = "SELECT brand_id,brand_name FROM brands";
$brand_result = mysqli_query($conn, $brand_sql);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $brand_id = mysqli_real_escape_string($conn, $_POST['brand_id']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $base_price = !empty($_POST['base_price']) ? mysqli_real_escape_string($conn, $_POST['base_price']) : $price;
    $qty = !empty($_POST['quantity']) ? mysqli_real_escape_string($conn, $_POST['quantity']) : 0;
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
    if (!preg_match('/^[0-9]+(\.[0-9]{1,2})?$/', $qty) || $qty <= 0) {
        $errorMessages[] = "Qtyត្រូវតែវិជ្ជមាន (ធំជាងសូន្យ)";
    }
    if ($base_price < $price) {
        $errorMessages[] = "Base Price ត្រូវធំជាង ឬ ស្មើរតម្លៃលក់ 🥹";
    }


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
        $img = $product['img'];
    }

    if (!empty($errorMessages)) {
        foreach ($errorMessages as $msg) {
            $messages[] = ['text' => $msg, 'type' => 'danger'];
        }
    } else {
 
        $sql = "UPDATE products SET name='$product_name', category_id='$category_id', img='$img', description='$description', price='$price', base_price='$base_price', brand_id='$brand_id', status='$status' WHERE product_id='$product_id'";

        if (mysqli_query($conn, $sql)) {
            $inventory_sql = "UPDATE inventory SET quantity='$qty' WHERE product_id='$product_id'";



        if (mysqli_query($conn, $inventory_sql)) {
            if (empty($messages)) {
                $_SESSION['message'] = ['text' => "ទំនិញត្រូវបានកែប្រែ🧙", 'type' => 'success'];
                echo " <script>window.location.href = 'index.php?pg=product'; </script>";

            }
        } else {
            $messages[] = ['text' => "Error: " . mysqli_error($conn), 'type' => 'danger'];
        }
    }
}
    
}
?>

<div class="container shadow-sm p-3 mt-5 bg-body-tertiary rounded">
    <div class="container p-4 ">
        <a class="nav-link" href="index.php?pg=product">
            <i class="fas fa-chevron-left"></i>
            <span>ទំនិញ</span>
        </a>

        <h2 class="text-center mb-5">កែប្រែទំនិញ</h2>
        <hr class="sidebar-divider">

        <?php require_once('page/message.php') ?>

        <form action="index.php?pg=edit_product&id=<?= $product_id ?>" method="POST" enctype="multipart/form-data">
            <div id="message" class="alert" style="display: none;"></div>

            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="product_name">ឈ្មោះទំនិញ</label>
                        <input type="text" class="form-control" id="product_name" name="product_name"
                            value="<?= htmlspecialchars($product_name) ?>" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="price">តម្លៃ (USD)</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price"
                            value="<?= htmlspecialchars($price) ?>" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="base_price">Base Price (USD)</label>
                        <input type="number" step="0.01" class="form-control" id="base_price" name="base_price"
                            value="<?= htmlspecialchars($base_price) ?>" required>
                    </div>
                </div>
            </div>

            <div class="form-row">
            <div class="col-md-3">
                    <div class="form-group">
                        <label for="base_price">Qty</label>
                        <input type="number" step="0.01" class="form-control" id="quantity" name="quantity"
                            value="<?= htmlspecialchars($qty) ?>" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            <?php while ($row = mysqli_fetch_assoc($category_result)): ?>
                            <option value="<?= $row['category_id'] ?>"
                                <?= ($row['category_id'] == $category_id) ? 'selected' : '' ?>><?= $row['category_name'] ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="brand_id">Brand</label>
                        <select class="form-control" id="brand_id" name="brand_id" required>
                            <?php while ($row = mysqli_fetch_assoc($brand_result)): ?>
                            <option value="<?= $row['brand_id'] ?>"
                                <?= ($row['brand_id'] == $brand_id) ? 'selected' : '' ?>><?= $row['brand_name'] ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="img">រូបភាព</label>
                                <input type="file" class="form-control-file" id="img" name="img" accept="image/*"
                                    style="display: none;" onchange="previewImage(event)">

                                <!-- Display the old image if available -->
                                <?php if (!empty($img)): ?>
                                <div class="mt-2">
                                    <br>
                                    <label for="img">
                                        <img src="../database/img/product/<?= htmlspecialchars($img) ?>" alt="Old Image"
                                            style="max-width: 200px; max-height: 200px; cursor: pointer;">
                                    </label>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="description">បរិយាយ</label>
                        <textarea class="form-control" id="description" name="description"
                            rows="5"><?= htmlspecialchars($description) ?></textarea>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group">
                    <div class="col-md-6">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="available" <?= ($status == 'available') ? 'selected' : '' ?>>Avilable</option>
                            <option value="unavailable" <?= ($status == 'unavailable') ? 'selected' : '' ?>>Unavilable</option>
                            <option value="pending" <?= ($status == 'pending') ? 'selected' : '' ?>>Pending</option>
                        </select>
                    </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Update</button>
        </form>
    </div>
</div>
<script>
    function previewImage(event) {
        const imgElement = document.querySelector('label[for="img"] img');
        if (event.target.files && event.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                imgElement.src = e.target.result; 
            };
            reader.readAsDataURL(event.target.files[0]); 
        }
    }
</script>