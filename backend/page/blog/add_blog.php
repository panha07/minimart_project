<?php

$title = $image_url = $post_description = $comments_count = $status = $post_by_id = "";
$errorMessages = [];
$messages = [];

// Fetch active users for the "posted by" selection
$sql = "SELECT user_id, CONCAT(first_name, ' ', last_name) AS username FROM users WHERE status='active'";
$user_result = mysqli_query($conn, $sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $post_description = mysqli_real_escape_string($conn, $_POST['post_description']);
    $comments_count = mysqli_real_escape_string($conn, $_POST['comments_count']);
    $post_by_id = mysqli_real_escape_string($conn, $_POST['post_by_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    // Handle file upload for image
    if (isset($_FILES['image_url']['name']) && $_FILES['image_url']['name'] != "") {
        $target_dir = "../database/img/blog/";
        $image_url = basename($_FILES["image_url"]["name"]);
        $target_file = $target_dir . $image_url;

        // Check if the uploaded file is an image
        $check = getimagesize($_FILES["image_url"]["tmp_name"]);
        if ($check === false) {
            $errorMessages[] = "The file is not an image.";
        }

        // Move uploaded file if no error
        if (empty($errorMessages)) {
            move_uploaded_file($_FILES["image_url"]["tmp_name"], $target_file);
        }
    } else {
        $errorMessages[] = "សូមជ្រើសរើសរូបភាព";
    }

    // Validate title
    if (empty($title)) {
        $errorMessages[] = "ចំណងជើងមិនអាចទទេរបានទេ";
    }

    // Validate comments_count (optional)
    if (!empty($comments_count) && !is_numeric($comments_count)) {
        $errorMessages[] = "ចំនួន Commentsគឺជាលេខ ";
    }

    // Validate status
    if (empty($status)) {
        $errorMessages[] = "សូមជ្រើសរើស status.";
    }

    // Check for errors
    if (!empty($errorMessages)) {
        foreach ($errorMessages as $msg) {
            $messages[] = ['text' => $msg, 'type' => 'danger'];
        }
    } else {
       
        $sql = "INSERT INTO blog_posts (title, image_url, post_description, comments_count, status, post_by_id, post_date)
                VALUES ('$title', '$image_url', '$post_description', '$comments_count', '$status', '$post_by_id', NOW())";

        if (mysqli_query($conn, $sql)) {
            $messages[] = ['text' => "បានបញ្ចូល Blog ដោយជោកជ័យ🧙‍♂️", 'type' => 'success'];
        } else {
            $messages[] = ['text' => "Error: " . mysqli_error($conn), 'type' => 'danger'];
        }
    }
}
?>

<div class="container shadow-sm p-3 mt-5 bg-body-tertiary rounded">
    <div class="container p-4">
        <a class="nav-link" href="index.php?pg=blog">
            <i class="fas fa-chevron-left"></i>
            <span>Blog</span>
        </a>

        <h2 class="text-center mb-5">បន្ថែម Blog Post</h2>
        <hr class="sidebar-divider">

        <?php require_once('page/message.php') ?>

        <form action="index.php?pg=add_blog" method="POST" enctype="multipart/form-data">
            <div id="message" class="alert" style="display: none;"></div>

            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">ចំណងជើង</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="comments_count">ចំនួន Comments</label>
                        <input type="number" class="form-control" id="comments_count" name="comments_count" value="0">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="post_by_id">បង្ហោះដោយ</label>
                        <select class="form-control" id="post_by_id" name="post_by_id" required>
                            <?php while ($row = mysqli_fetch_assoc($user_result)): ?>
                                <option value="<?= $row['user_id'] ?>"><?= htmlspecialchars($row['username']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="post_description">Description</label>
                <textarea class="form-control" id="post_description" name="post_description" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="image_url">រូបភាព</label>
                <input type="file" class="form-control-file" id="image_url" name="image_url" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">បន្ថែម Blog Post</button>
        </form>
    </div>
</div>
