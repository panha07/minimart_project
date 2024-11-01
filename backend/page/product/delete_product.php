<?php
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']); 
    $sql = "DELETE FROM products WHERE product_id = $product_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = ['text' => "ទំនិញត្រូវបានលុប🧙", 'type' => 'success'];
    } else {
        $_SESSION['message'] = ['text' => "លុបមិនបាន🤦" . mysqli_error($conn), 'type' => 'danger'];
    }
    echo " <script>window.location.href = 'index.php?pg=product'; </script>";
    exit();
}

?>