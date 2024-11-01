<?php
$status = isset($_GET['status']) ? $_GET['status'] : 'available'; 

$sql = "SELECT blog_posts.post_id, title, image_url, post_description, CONCAT(first_name, ' ', last_name) AS username, post_date, blog_posts.created_at, blog_posts.status 
        FROM blog_posts 
        INNER JOIN users ON users.user_id = blog_posts.post_by_id 
        WHERE blog_posts.status = '$status'";

$result = mysqli_query($conn, $sql);
$num_rows = mysqli_num_rows($result);

if (isset($_SESSION['message'])) {
    $messages[] = $_SESSION['message']; 
    unset($_SESSION['message']); 
}
function truncateText($text, $length = 60) {
    if (strlen($text) > $length) {
        return substr($text, 0, $length+1) . '...';
    }
    return $text;
}


function displayBlogs($result) {
    if (mysqli_num_rows($result) > 0) {
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered table-hover table-striped">';
        echo '<thead class="thead-dark">
                <tr>
                    <th scope="col" class="text-center">លេខរៀង</th>
                    <th scope="col" class="text-center">ចំណងជើង</th>
                    <th scope="col" class="text-center">រូបភាព</th>
                    <th scope="col" class="text-center">អ្នកបង្កើត Blog</th>
                    <th scope="col" class="text-center">ពណ៌នាអំពី</th>
                    <th scope="col" class="text-center">ថ្ងៃ Post</th>
                    <th scope="col" class="text-center">ថ្ងៃបង្កើត</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>';
        echo '<tbody>';
        $i = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            
            // Determine status-related action icons and titles
            if ($row['status'] == 'available') {
                $toggleIcon = '<i class="bi bi-eye-slash-fill"></i>'; 
                $toggleTitle = 'Mark as Unavailable'; 
                $toggleStatus = 'unavailable';
            } elseif ($row['status'] == 'unavailable') {
                $toggleIcon = '<i class="bi bi-eye-fill"></i>'; 
                $toggleTitle = 'Mark as Available'; 
                $toggleStatus = 'available';
            } else {
                $toggleIcon = ''; 
                $toggleStatus = '';
                $toggleTitle = ''; 
            }

            echo '<tr>';
            echo '<td class="text-center">' . $i++ . '</td>';
            echo '<td class="text-center">' . $row['title'] . '</td>';
            echo '<td class="text-center"><img src="../database/img/blog/' . $row['image_url'] . '" alt="' . $row['title'] . '" class="img-thumbnail" style="width: 35px; height: 30px; object-fit: cover;"></td>';
            echo '<td class="text-center">' . $row['username'] . '</td>';
            echo '<td>' .truncateText( $row['post_description'] ). '</td>';
            echo '<td class="text-center">' . $row['post_date'] . '</td>';
            echo '<td class="text-center">' . $row['created_at'] . '</td>';

            echo '<td class="text-center">
                    <div class="btn-group" role="group" aria-label="Blog Actions">
                        <a href="index.php?pg=edit_blog&id=' . $row['post_id'] . '" class="btn mr-2 btn-outline-primary  btn-sm " title="Edit Blog"><i class="bi bi-pencil-fill"></i></a>
                        <a href="javascript:void(0);" class="btn  btn-outline-danger mr-2 btn-sm" title="Delete Blog" onclick="confirmDelete(' . $row['post_id'] . ')"><i class="bi bi-trash-fill"></i></a>';

            if (!empty($toggleIcon)) {
                echo '<a href="index.php?pg=toggle_blog&id=' . $row['post_id'] . '&status=' . $toggleStatus . '" class="btn  btn-outline-secondary btn-sm" title="' . $toggleTitle . '">' . $toggleIcon . '</a>';
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
        ពុំមានប្លុកទេ!
        </p>';
    }
}
?>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-center">គ្រប់គ្រងBlog</h2>
        <a href="index.php?pg=add_blog" class="btn btn-primary">បន្ថែមBlog</a>
    </div>
    <?php include_once('page/message.php'); ?> 

    <ul class="nav nav-tabs" id="blogTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link <?= ($status == 'available') ? 'active' : '' ?>" href="index.php?pg=blog&status=available">Available</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($status == 'unavailable') ? 'active' : '' ?>" href="index.php?pg=blog&status=unavailable">Unavailable</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($status == 'pending') ? 'active' : '' ?>" href="index.php?pg=blog&status=pending">Pending</a>
        </li>
    </ul>

    <div class="tab-content" id="blogTabsContent">
        <div class="tab-pane fade <?= ($status == 'available') ? 'show active' : '' ?>" id="available" role="tabpanel">
            <?php if ($status == 'available') displayBlogs($result); ?>
        </div>
        <div class="tab-pane fade <?= ($status == 'unavailable') ? 'show active' : '' ?>" id="unavailable" role="tabpanel">
            <?php if ($status == 'unavailable') displayBlogs($result); ?>
        </div>
        <div class="tab-pane fade <?= ($status == 'pending') ? 'show active' : '' ?>" id="pending" role="tabpanel">
            <?php if ($status == 'pending') displayBlogs($result); ?>
        </div>
    </div>
</div>

<script>
    function confirmDelete(blogId) {
        if (confirm("Do you want to delete this blog?")) {
            window.location.href = 'index.php?pg=delete_blog&id=' + blogId;
        }
    }
</script>
