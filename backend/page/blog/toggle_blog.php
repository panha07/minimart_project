<?php
if (isset($_GET['id']) && $_GET['status']) {
    $id = $_GET['id'];
    $new_status = $_GET['status'];

    $id = mysqli_real_escape_string($conn, $id);
    $new_status = mysqli_real_escape_string($conn, $new_status);

    // Update the product status in the database
    $sql = "UPDATE blog_posts SET status = '$new_status' WHERE post_id = '$id'";
    if (mysqli_query($conn, $sql)) {
        // If the update is successful, set a success message
        $_SESSION['message'] = ['text' => "Blog ត្រូវបានកែប្រែ🧙", 'type' => 'success'];
    } else {
        $_SESSION['message'] = ['text' => "មិនអាចកែប្រែទេ🤷", 'type' => 'error'];
        
    }

 
    echo " <script>window.location.href = 'index.php?pg=blog'; </script>";
    exit();
}

?>