<?php
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$title = $post_description = $image_url = $status = $comments_count = $post_by_id = " ";

if ($post_id > 0) {
    $sql = "SELECT post_id, title, image_url, post_description, comments_count, blog_posts.status, post_by_id, gender
            FROM blog_posts 
            INNER JOIN users ON users.user_id = blog_posts.post_by_id 
            WHERE users.status = 'active' 
            AND blog_posts.status = 'available' 
            AND post_id = '$post_id' 
            ORDER BY blog_posts.post_date;";
    $blog_result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($blog_result) > 0) {
        $blog = mysqli_fetch_assoc($blog_result);
        $title = $blog['title'];
        $post_description = $blog['post_description'];
        $image_url = $blog['image_url'];
        $status = $blog['status'];
        $comments_count = $blog['comments_count'];
        $post_by_id = $blog['post_by_id'];
        $gender_title = "";
            if ($blog['gender'] == 'male') {
                 $gender_title = "លោក"; 
            } elseif ($blog['gender'] == 'female') {
                $gender_title = "កញ្ញា/លោកស្រី"; 
            } else {
                  $gender_title = "";
            }



    } else {
        $messages[] = ['text' => "Post not found!", 'type' => 'danger'];
    }
} else {
    $messages[] = ['text' => "Post not found!", 'type' => 'danger'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $post_description = mysqli_real_escape_string($conn, $_POST['post_description']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $post_by_id= mysqli_real_escape_string($conn, $_POST['post_by_id']);

    

    $errorMessages = [];
    $messages = [];

    // Validate title
    if (empty($title)) {
        $errorMessages[] = "ចំណងជើងត្រូវតែមានអក្សរ";
    }

    // Image upload handling
    if (isset($_FILES['img']['name']) && $_FILES['img']['name'] != "") {
        $target_dir = "../database/img/blog/";
        $img = basename($_FILES["img"]["name"]);
        $target_file = $target_dir . $img;

        $check = getimagesize($_FILES["img"]["tmp_name"]);
        if ($check === false) {
            $errorMessages[] = "សូមជ្រើសរើសរូបភាព";
        }


        if (empty($errorMessages)) {
            move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
        }
    } else {
        $img = $image_url;
    }

    if (!empty($errorMessages)) {
        foreach ($errorMessages as $msg) {
            $messages[] = ['text' => $msg, 'type' => 'danger'];
        }
    } else {
        $sql = "UPDATE blog_posts
        SET title = '$title', post_description = '$post_description', 
            image_url = '$img', status = '$status', post_by_id ='$post_by_id'
        WHERE post_id = '$post_id'";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = ['text' => "Blog ត្រូវបានកែប្រែ🧙", 'type' => 'success'];
            echo "<script>
    window.location.href = 'index.php?pg=blog';
</script>";
        } else {
            $messages[] = ['text' => "Error: " . mysqli_error($conn), 'type' => 'danger'];
        }
    }
}

    $user_sql = "SELECT user_id, CONCAT(first_name, ' ', last_name) AS full_name FROM users WHERE status = 'active'";
    $user_result = mysqli_query($conn, $user_sql);
    $users = [];
    while ($user = mysqli_fetch_assoc($user_result)) {
        $users[] = $user;
    }
?>

<div class="container shadow-sm p-3 mt-5 mb-5 bg-body-tertiary rounded">
    <div class="container p-4">
        <a class="nav-link" href="index.php?pg=blog">
            <i class="fas fa-chevron-left"></i>
            <span>Blog</span>
        </a>
       

        <h2 class="text-center mb-5">កែប្រែ Blog </h2>
        <hr class="sidebar-divider">

        <?php require_once('page/message.php'); ?>

        <form action="index.php?pg=edit_blog&id=<?= $post_id ?>" method="POST" enctype="multipart/form-data">
            <div id="message" class="alert" style="display: none;"></div>

            <div class="form-row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="title">ចំណងជើង</label>
                        <input type="text" class="form-control" id="title" name="title"
                            value="<?= htmlspecialchars($title) ?>" required>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="post_description">Description</label>
                        <textarea class="form-control" id="post_description" name="post_description"
                            rows="5"><?= ($post_description) ?></textarea>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="available" <?= ($status == 'available') ? 'selected' : '' ?>>Available
                            </option>
                            <option value="unavailable" <?= ($status == 'unavailable') ? 'selected' : '' ?>>Unavailable
                            </option>
                            <option value="pending" <?= ($status == 'pending') ? 'selected' : '' ?>>Pending</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                   

                    <div class="form-group">
                        <label for="post_by_id">បង្ហោះដោយ ៖ </label>
                        <select class="form-control" id="post_by_id" name="post_by_id" required>
                            <?php foreach ($users as $user): ?>
                            <option value="<?= $user['user_id'] ?>"
                                <?= ($user['user_id'] == $post_by_id) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($user['full_name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                </div>



            </div>



            <div class="form-row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="img">រូបភាព</label>
                        <input type="file" class="form-control-file" id="img" name="img" accept="image/*"
                            style="display: none;" onchange="previewImage(event)">
                        <?php if (!empty($image_url)): ?>
                        <div class="mt-2">

                            <label for="img">
                                <img src="../database/img/blog/<?= htmlspecialchars($image_url) ?>" alt="Old Image"
                                    style="max-width: 200px; max-height: 200px; cursor: pointer;">
                            </label>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Update Blog Post</button>
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