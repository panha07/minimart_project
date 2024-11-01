<?php
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 
    $sql = "DELETE FROM users WHERE user_id = $id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = ['text' => "User ត្រូវបានលុប🧙", 'type' => 'success'];
    } else {
        $_SESSION['message'] = ['text' => "លុបមិនបាន🤦" . mysqli_error($conn), 'type' => 'danger'];
    }
    echo " <script>window.location.href = 'index.php?pg=user'; </script>";
    exit();
}

?>