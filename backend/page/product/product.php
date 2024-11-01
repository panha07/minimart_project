<?php
$status = isset($_GET['status']) ? $_GET['status'] : 'available'; 

$sql = "SELECT p.product_id, p.name AS product_name, p.img, p.description,
         p.price, p.base_price, p.status, b.brand_name, c.category_name as category_name,
        quantity
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.category_id 
        LEFT JOIN brands b ON p.brand_id = b.brand_id
        inner join inventory i on p.product_id=i.product_id
        WHERE p.status = '$status' AND c.status = 'active'";
        
$result = mysqli_query($conn, $sql);
$num_rows = mysqli_num_rows($result);
while ($row = mysqli_fetch_assoc($result)) {
    if ($row['quantity'] == 0) {
        
        $update_sql = "UPDATE products SET status='unavailable' WHERE product_id='" . $row['product_id'] . "'";
        mysqli_query($conn, $update_sql);
    }
}
$result = mysqli_query($conn, $sql);

if (isset($_SESSION['message'])) {
    $messages[] = $_SESSION['message']; 
    unset($_SESSION['message']); 
}
$available_count = 0;
$unavailable_count = 0;
$pending_count = 0;

// Count products based on status
$count_sql = "SELECT status, COUNT(*) as count FROM products WHERE status IN ('available', 'unavailable', 'pending') GROUP BY status";
$count_result = mysqli_query($conn, $count_sql);

while ($row = mysqli_fetch_assoc($count_result)) {
    if ($row['status'] == 'available') {
        $available_count = $row['count'];
    } elseif ($row['status'] == 'unavailable') {
        $unavailable_count = $row['count'];
    } elseif ($row['status'] == 'pending') {
        $pending_count = $row['count'];
    }
}


function displayProducts($result) {
    if (mysqli_num_rows($result) > 0) {
    
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered table-hover table-striped">';
        echo '<thead class="thead-dark">
                <tr>
                    <th scope="col"class="text-center">លេខរៀង</th>
                    <th scope="col"class="text-center">រូបភាពទំនិញ</th>
                    <th scope="col" class="text-center">ឈ្មោះទំនិញ</th>
                    <th scope="col" class="text-center">Category</th>
                    <th scope="col" class="text-center">Brand</th>
                    <th scope="col">តម្លៃលក់</th>
                    <th scope="col">តម្លៃ</th>
                    <th scope="col">ចំនួន</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>';
        echo '<tbody>';
        $i = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td class="text-center">' . $i++ . '</td>';
            echo '<td class="text-center"><img src="../database/img/product/' . $row['img'] . '" alt="' . $row['product_name'] . '" class="img-thumbnail" style="width: 35px; height: 30px; object-fit: cover;"></td>';
            echo '<td class="text-center">' . $row['product_name'] . '</td>';
            echo '<td class="text-center">' . $row['category_name'] . '</td>';
            echo '<td class="text-center">' . $row['brand_name'] . '</td>';
            echo '<td>$' . number_format($row['price'], 2) . '</td>';
            echo '<td>$' . number_format($row['base_price'], 2) . '</td>';
            echo '<td class="text-center">' .($row['quantity']) . '</td>';
           

            if ($row['status'] == 'available') {
                $toggleIcon = '<i class="bi bi-eye-slash-fill"></i>'; 
                $toggleTitle = 'Mark as pending'; 
                $toggleStatus = 'pending';
            

            } elseif ($row['status'] == 'pending') {
                $toggleIcon = '<i class="bi bi-eye-fill"></i>'; 
                $toggleTitle = 'Mark as Available'; 
                $toggleStatus = 'available';
            } else {
                $toggleIcon = ''; 
                $toggleStatus = '';
                $toggleTitle = ''; 
            }

            echo '<td class="text-center">
                    <div class="btn-group" role="group" aria-label="Product Actions">
                        <a href="index.php?pg=edit_product&id=' . $row['product_id'] . '"class="btn mr-2 btn-outline-primary  btn-sm "  title="Edit Product"><i class="bi bi-pencil-fill"></i></a>
                        <a href="javascript:void(0);" class="btn  mr-2 btn-outline-danger btn-sm" title="Delete Product" onclick="confirmDelete(' . $row['product_id'] . ')"><i class="bi bi-trash-fill"></i></a>';
            
        
            if (!empty($toggleIcon)) {
                echo '<a href="index.php?pg=toggle_product&id=' . $row['product_id'] . '&status=' . $toggleStatus . '" class="btn btn-outline-info  mr-2 btn-sm" title="' . $toggleTitle . '">' . $toggleIcon . '</a>';
            }

            echo '</div></td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        echo '<p class="text-center">
        </br>
        ពុំមានទំនិញទេ!
        </p>';
    }
}



?>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-center">គ្រប់គ្រងទំនិញ</h2>
        <a href="index.php?pg=add_product" class="btn btn-primary">បន្ថែមទំនិញ</a>

    </div>
   <?php include_once('page/message.php') ?> 

 
    <ul class="nav nav-tabs" id="productTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link <?= ($status == 'available') ? 'active' : '' ?>" href="index.php?status=available">Available (<?= $available_count?>)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($status == 'unavailable') ? 'active' : '' ?>" href="index.php?status=unavailable">Unavailable (<?=$unavailable_count?>)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($status == 'pending') ? 'active' : '' ?>" href="index.php?status=pending">Pending (<?= $pending_count?>)</a>
        </li>
    </ul>

    <div class="tab-content" id="productTabsContent">
        <div class="tab-pane fade <?= ($status == 'available') ? 'show active' : '' ?>" id="available" role="tabpanel">
            <?php if ($status == 'available') displayProducts($result); ?>
        </div>
        <div class="tab-pane fade <?= ($status == 'unavailable') ? 'show active' : '' ?>" id="unavailable" role="tabpanel">
            <?php if ($status == 'unavailable') displayProducts($result); ?>
        </div>
        <div class="tab-pane fade <?= ($status == 'pending') ? 'show active' : '' ?>" id="pending" role="tabpanel">
            <?php if ($status == 'pending') displayProducts($result); ?>
        </div>
    </div>
  
</div>

<script>
    function confirmDelete(productId) {
      
        if (confirm("Do you want to delete this product?")) {
            window.location.href = 'index.php?pg=delete_product&id=' + productId;
        }
    }
</script>