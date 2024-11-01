<?php
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 
    $sql = "DELETE FROM categories WHERE category_id = $id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = ['text' => "Category ត្រូវបានលុប🧙", 'type' => 'success'];
    } else {
        $_SESSION['message'] = ['text' => "លុបមិនបាន🤦" . mysqli_error($conn), 'type' => 'danger'];
    }
    echo " <script>window.location.href = 'index.php?pg=category'; </script>";
    exit();
}

?>