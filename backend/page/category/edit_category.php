<?php
if (isset($_GET['id'])) {
    $category_id = $_GET['id'];
    $sql = "SELECT * FROM categories WHERE category_id = $category_id";
    $result = mysqli_query($conn, $sql);

    $messages = [];


    if (mysqli_num_rows($result) == 1) {
        $category = mysqli_fetch_assoc($result);
    } else {
        echo "រកប្រភេទទំនិញពុំឃើញ";
        exit;
    }
}
if (isset($_POST['update_category'])) {
    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $sql = "UPDATE categories SET 
            category_name = '$category_name', 
            description = '$description', 
            status = '$status', 
            updated_at = NOW() 
            WHERE category_id = $category_id";

    if (mysqli_query($conn, $sql)) {

        $_SESSION['message'] = ['text' => "ប្រភេទទំនិញត្រូវបានកែប្រែ🧙", 'type' => 'success'];
        echo "<script>window.location.href = 'index.php?pg=category';</script>";
    } else {
        $messages[] = ['text' => "Error: " . mysqli_error($conn), 'type' => 'danger'];
    }
}
?>
<div class="container shadow-sm p-3 mt-5 bg-body-tertiary rounded">
    <div class="container p-4">
        <a class="nav-link" href="index.php?pg=category">
            <i class="fas fa-chevron-left"></i>
            <span>ប្រភេទទំនិញ</span>
        </a>

        <h2 class="text-center mb-5">កែប្រែប្រភេទទំនិញ</h2>
        <hr class="sidebar-divider">

        <?php require_once('page/message.php') ?>

        <form action="" method="post">
            <div id="message" class="alert" style="display: none;"></div>

            <div class="form-row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="name">ឈ្មោះប្រភេទទំនិញ</label>
                        <input type="text" name="category_name" class="form-control"
                            value="<?= htmlspecialchars($category['category_name']) ?>" required>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($category['description']) ?></textarea>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-control">
                            <option value="active" <?= $category['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= $category['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" name="update_category" class="btn btn-primary btn-block">រក្សាទុក</button>
            
        </form>
    </div>
</div>


<script>
    function previewImage(event) {
        const imgElement = document.querySelector('label[for="img"] img');
        if (event.target.files && event.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imgElement.src = e.target.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>