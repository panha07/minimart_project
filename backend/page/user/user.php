<?php
// Assume $conn is your database connection
$status = isset($_GET['status']) ? $_GET['status'] : 'active'; 
$selectedRole = isset($_GET['role']) ? $_GET['role'] : '';

// Adjust the SQL query to show users based on selected role
$sql = "SELECT user_id, first_name, last_name, email, gender, created_at, role, status
        FROM users
        WHERE status = '$status'";

if ($selectedRole === 'admin') {
    $sql .= " AND role = 'admin'";
} elseif ($selectedRole === 'normal') {
    $sql .= " AND role = 'normal'";
}

$result = mysqli_query($conn, $sql);
$num_rows = mysqli_num_rows($result);
if (isset($_SESSION['message'])) {
    $messages[] = $_SESSION['message']; 
    unset($_SESSION['message']); 
}

function displayUsers($result) {
    if (mysqli_num_rows($result) > 0) {
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered table-hover table-striped">';
        echo '<thead class="thead-dark">
                <tr>
                    <th scope="col" class="text-center">លេខរៀង</th>
                    <th scope="col" class="text-center">ឈ្មោះ</th>
                    <th scope="col" class="text-center">អ៊ីមែល</th>
                    <th scope="col" class="text-center">ភេទ</th>
                    <th scope="col" class="text-center">តួនាទី</th>
                    <th scope="col" class="text-center">ថ្ងៃបង្កើត</th>
                    <th scope="col" class="text-center">ស្ថានភាព</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>';
        echo '<tbody>';
        $i = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td class="text-center">' . $i++ . '</td>';
            echo '<td class="text-center">' . $row['first_name'] . ' ' . $row['last_name'] . '</td>';
            echo '<td class="text-center">' . $row['email'] . '</td>';
            echo '<td class="text-center">' . ucfirst($row['gender']) . '</td>';
            echo '<td class="text-center">' . ucfirst($row['role']) . '</td>';
            echo '<td class="text-center">' . date('Y-m-d', strtotime($row['created_at'])) . '</td>';
            echo '<td class="text-center">' . ucfirst($row['status']) . '</td>';

            // Toggle status based on current status
            if ($row['status'] == 'active') {
                $toggleIcon = '<i class="bi bi-eye-slash-fill"></i>'; 
                $toggleTitle = 'Deactivate User'; 
                $toggleStatus = 'inactive';
            } elseif ($row['status'] == 'inactive') {
                $toggleIcon = '<i class="bi bi-eye-fill"></i>'; 
                $toggleTitle = 'Activate User'; 
                $toggleStatus = 'active';
            } else {
                $toggleIcon = ''; 
                $toggleStatus = '';
                $toggleTitle = ''; 
            }

            echo '<td class="text-center">
                    <div class="btn-group" role="group" aria-label="User Actions">
                        <a href="index.php?pg=edit_user&id=' . $row['user_id'] . '" class="btn mr-2 btn-outline-primary btn-sm" title="Edit User"><i class="bi bi-pencil-fill"></i></a>
                        <a href="javascript:void(0);" class="btn mr-2 btn-outline-danger btn-sm" title="Delete User" onclick="confirmDelete(' . $row['user_id'] . ')"><i class="bi bi-trash-fill"></i></a>';
            
            if (!empty($toggleIcon)) {
                echo '<a href="index.php?pg=toggle_user&id=' . $row['user_id'] . '&status=' . $toggleStatus . '" class="btn btn-outline-info mr-2 btn-sm" title="' . $toggleTitle . '">' . $toggleIcon . '</a>';
            }

            echo '</div></td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        echo '<p class="text-center"><br>ពុំមានអ្នកប្រើប្រាស់ទេ!</p>';
    }
}

?>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-center">គ្រប់គ្រងអ្នកប្រើប្រាស់</h2>
        <a href="index.php?pg=add_user" class="btn btn-primary">បន្ថែមអ្នកប្រើប្រាស់</a>
    </div>
    <?php include_once('page/message.php') ?> 
    <div class="mb-4">
        <form method="GET" action="index.php?pg=user">
            <div class="form-row align-items-center">
                <div class="col-auto">
                    <label class="sr-only" for="roleSelect">តួនាទី</label>
                    <select name="role" id="roleSelect" class="form-control" onchange="this.form.submit()">
                        <option value="">All Roles</option>
                        <a href="index.php?pg=user"><option value="admin" <?= $selectedRole == 'admin' ? 'selected' : '' ?>>Admin</option></a>
                        <a href="index.php?pg=user"> <option value="normal" <?= $selectedRole == 'normal' ? 'selected' : '' ?>>Normal</option></a>
                        
                       
                    </select>
                </div>
            </div>
        </form>
    </div>

    <ul class="nav nav-tabs" id="userTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link <?= ($status == 'active') ? 'active' : '' ?>" href="index.php?pg=user&status=active">Active</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($status == 'inactive') ? 'active' : '' ?>" href="index.php?pg=user&status=inactive">Inactive</a>
        </li>
    </ul>
    
    <div class="tab-content" id="userTabsContent">
        <div class="tab-pane fade <?= ($status == 'active') ? 'show active' : '' ?>" id="active" role="tabpanel">
            <?php if ($status == 'active') displayUsers($result); ?>
        </div>
        <div class="tab-pane fade <?= ($status == 'inactive') ? 'show active' : '' ?>" id="inactive" role="tabpanel">
            <?php if ($status == 'inactive') displayUsers($result); ?>
        </div>
    </div>
</div>

<script>
    function confirmDelete(userId) {
        if (confirm("Do you want to delete this user?")) {
            window.location.href = 'index.php?pg=delete_user&id=' + userId;
        }
    }
</script>
