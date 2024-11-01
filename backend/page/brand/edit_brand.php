<?php

$brand_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$brand_name = $create_date = $update_date = "";

if ($brand_id > 0) {
    $sql = "SELECT * FROM brands WHERE brand_id = '$brand_id'";
    $brand_result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($brand_result) > 0) {
        $brand = mysqli_fetch_assoc($brand_result);
        $brand_name = $brand['brand_name'];
        $create_date = $brand['create_date'];
        $update_date = $brand['update_date'];
    } else {
        $messages[] = ['text' => "Brand not found!", 'type' => 'danger'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brand_name = mysqli_real_escape_string($conn, $_POST['brand_name']);

    $errorMessages = [];
    $messages = [];

    if (empty($brand_name)) {
        $errorMessages[] = "ឈ្មោះ Brandមិនអាចទទេរបានទេ";
    } elseif (!preg_match('/^[\p{L}\s]+$/u', $brand_name)) {
        $errorMessages[] = "ខុសឈ្មោះ brand ";
    }

    if (empty($errorMessages)) {
        $sql = "UPDATE brands SET brand_name='$brand_name', update_date=NOW() WHERE brand_id='$brand_id'";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = ['text' => "Brand ត្រូវបានកែប្រែ🧙", 'type' => 'success'];
            echo "<script>window.location.href = 'index.php?pg=brand'; </script>";
            exit();
        } else {
            $messages[] = ['text' => "Error: " . mysqli_error($conn), 'type' => 'danger'];
        }
    } else {
        foreach ($errorMessages as $msg) {
            $messages[] = ['text' => $msg, 'type' => 'danger'];
        }
    }
}
?>

<div class="container shadow-sm p-3 mt-5 bg-body-tertiary rounded">
    <div class="container p-4 ">
        <a class="nav-link" href="index.php?pg=brands">
            <i class="fas fa-chevron-left"></i>
            <span>Brands</span>
        </a>

        <h2 class="text-center mb-5">Edit Brand</h2>
        <hr class="sidebar-divider">

        <?php require_once('page/message.php') ?>

        <form action="index.php?pg=edit_brand&id=<?= $brand_id ?>" method="POST">
            <div class="form-row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="brand_name">Brand Name</label>
                        <input type="text" class="form-control" id="brand_name" name="brand_name"
                               value="<?= htmlspecialchars($brand_name) ?>" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Update</button>
        </form>
    </div>
</div>
