<?php
$status = isset($_GET['status']) ? $_GET['status'] : 'active';
$sql = "SELECT * FROM categories WHERE status = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $status);
$stmt->execute();
$result = $stmt->get_result();

if (isset($_SESSION['message'])) {
    $messages[] = $_SESSION['message'];
    unset($_SESSION['message']);
}

function displayCategories($result)
{
    if (mysqli_num_rows($result) > 0) {
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered table-hover table-striped">';
        echo '<thead class="thead-dark">
                <tr>
                    <th scope="col" class="text-center">លេខរៀង</th>
                    <th scope="col" class="text-center">ឈ្មោះប្រភេទ</th>
                    <th scope="col" class="text-center">សេចក្ដីពិពណ៌នា</th>
                    <th scope="col" class="text-center">កាលបរិច្ឆេទបង្កើត</th>
                    <th scope="col" class="text-center">កាលបរិច្ឆេទកែប្រែ</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>';
        echo '<tbody>';
        $i = 1;

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td class="text-center">' . $i++ . '</td>';
            echo '<td>' . htmlspecialchars($row['category_name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['description']) . '</td>';
            echo '<td>' . htmlspecialchars($row['created_at']) . '</td>';
            echo '<td>' . htmlspecialchars($row['updated_at']) . '</td>';

            // Determine toggle status and icons
            if ($row['status'] == 'active') {
                $toggleIcon = '<i class="bi bi-eye-slash-fill"></i>';
                $toggleTitle = 'Mark as Inactive';
                $toggleStatus = 'inactive';
            } elseif ($row['status'] == 'inactive') {
                $toggleIcon = '<i class="bi bi-eye-fill"></i>';
                $toggleTitle = 'Mark as Active';
                $toggleStatus = 'active';
            } else {
                $toggleIcon = '';
                $toggleStatus = '';
                $toggleTitle = '';
            }

            echo '<td>
                    <div class="btn-group" role="group" aria-label="Category Actions">
                        <a href="index.php?pg=edit_category&id=' . $row['category_id'] . '" class="btn btn-warning btn-sm" title="Edit Category"><i class="bi bi-pencil-fill"></i></a>
                         <a href="javascript:void(0);" class="btn btn-danger btn-sm" title="Delete Product" onclick="confirmDelete(' . $row['category_id'] . ')"><i class="bi bi-trash-fill"></i></a>';
                        if (!empty($toggleIcon)) {
                echo '<a href="index.php?pg=toggle_category&id=' . $row['category_id'] . '&status=' . $toggleStatus . '"
                                class="btn btn-info btn-sm" title="' . $toggleTitle . '">' . $toggleIcon . '</a>';
            }
            echo '</div>
                  </td>
                  </tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        echo '<p class="text-center">
                <br>
                ពុំមានប្រភេទទេ!</p>';
    }
}
?>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-center">គ្រប់គ្រងប្រភេទទំនិញ</h2>
        <a href="index.php?pg=add_category" class="btn btn-primary">បន្ថែមប្រភេទទំនិញ</a>
    </div>
    <?php include_once('page/message.php'); ?>
    <ul class="nav nav-tabs" id="categoryTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link <?= ($status == 'active') ? 'active' : '' ?>" href="index.php?pg=category&status=active">Active</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($status == 'inactive') ? 'active' : '' ?>" href="index.php?pg=category&status=inactive">Inactive</a>
        </li>
    </ul>
    <div class="tab-content" id="categoryTabsContent">
        <div class="tab-pane fade <?= ($status == 'active') ? 'show active' : '' ?>" id="active" role="tabpanel">
            <?php if ($status == 'active') displayCategories($result); ?>
        </div>
        <div class="tab-pane fade <?= ($status == 'inactive') ? 'show active' : '' ?>" id="inactive" role="tabpanel">
            <?php if ($status == 'inactive') displayCategories($result); ?>
        </div>
    </div>
</div>
<script>
    function confirmDelete(Id) {
      
        if (confirm("Do you want to delete this category?")) {
            window.location.href = 'index.php?pg=delete_category&id=' + Id;
        }
    }
</script>
