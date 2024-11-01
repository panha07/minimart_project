<?php
// Initialize variables
$first_name = $last_name = $email = $password = $dob = $img = $gender = $role = $status = "";
$errorMessages = [];
$messages = [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Validation checks
    if (empty($first_name)) {
        $errorMessages[] = "ត្រកូលត្រូវតែមាន";
    }
    
    if (empty($last_name)) {
        $errorMessages[] = "ឈ្មោះត្រូវតែមាន"; 
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessages[] = "អ៊ីមែលមិនត្រឹមត្រូវ"; 
    }

    if (empty($password)) {
        $errorMessages[] = "ពាក្យសម្ងាត់មិនអាចទទេ"; 
    }

    if (empty($errorMessages)) {
        $email_check_sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $email_check_sql);
        
        if (mysqli_num_rows($result) > 0) {
            $errorMessages[] = "អ៊ីមែលនេះបានត្រូវបានប្រើរួចហើយ"; 
        }
    }

    // Image upload handling
    if (isset($_FILES['img']['name']) && $_FILES['img']['name'] != "") {
        $target_dir = "../database/img/users/";
        $img = basename($_FILES["img"]["name"]);
        $target_file = $target_dir . $img;

        $check = getimagesize($_FILES["img"]["tmp_name"]);
        if ($check === false) {
            $errorMessages[] = "File នេះមិនមែនជា file រូបភាព"; 
        }

        if (empty($errorMessages)) {
            move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
        }
    } else {
        $errorMessages[] = "សូមជ្រើសរើសរូបភាពអ្នកប្រើ"; 
    }

    // Prepare messages based on error or success
    if (!empty($errorMessages)) {
        foreach ($errorMessages as $msg) {
            $messages[] = ['text' => $msg, 'type' => 'danger'];
        }
    } else {
        $sql = "INSERT INTO users (first_name, last_name, email, password, dob, img, gender, role, status)
                VALUES ('$first_name', '$last_name', '$email', '$password', '$dob', '$img', '$gender', '$role', '$status')";

        if (mysqli_query($conn, $sql)) {
            $messages[] = ['text' => "User ត្រូវបានបន្ថែមដោយជោគជ័យ🧙", 'type' => 'success']; 
        } else {
            $messages[] = ['text' => "Error: " . mysqli_error($conn), 'type' => 'danger'];
        }
    }
}

// Fetch roles from database (if roles are stored in a separate table, adjust accordingly)
$roles = ['normal', 'admin']; // Directly using enum values
?>

<div class="container shadow-sm p-3 mt-5 mb-5 bg-body-tertiary rounded">
    <div class="container p-4">
        <a class="nav-link" href="index.php?pg=user">
            <i class="fas fa-chevron-left"></i>
            <span>User</span>
        </a>

        <h2 class="text-center mb-5">បន្ថែម User</h2>
        <hr class="sidebar-divider">

        <?php require_once('page/message.php'); ?>

        <form action="index.php?pg=add_user" method="POST" enctype="multipart/form-data">
            <div id="message" class="alert" style="display: none;"></div>

            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="first_name">ត្រកូល</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="last_name">ឈ្មោះ</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">ពាក្យសម្ងាត់</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="dob">ថ្ងៃខែឆ្នាំកំណើត</label>
                        <input type="date" class="form-control" id="dob" name="dob">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="gender">ភេទ</label>
                        <select class="form-control" id="gender" name="gender">
                            <option value="male">ប្រុស</option>
                            <option value="female">ស្រី</option>
                            <option value="other">ផ្សេងៗ</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="role">តួនាទី</label>
                        <select class="form-control" id="role" name="role" required>
                            <?php foreach ($roles as $role_option): ?>
                                <option value="<?php echo $role_option; ?>"><?php echo ucfirst($role_option); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="img">រូបភាព</label>
                <input type="file" class="form-control-file" id="img" name="img" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">បន្ថែម User</button>
        </form>
    </div>
</div>
