<?php

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;


$first_name = $last_name = $gender = $email = $img = $role = $dob = "";
$messages = []; 

if ($user_id > 0) {
    $sql = "SELECT * FROM users WHERE user_id = '$user_id'";
    $user_result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($user_result) > 0) {
        $user = mysqli_fetch_assoc($user_result);
        $first_name = $user['first_name'];
        $last_name = $user['last_name'];
        $gender = $user['gender'];
        $email = $user['email'];
        $img = $user['img'];
        $role = $user['role'];
        $dob = $user['dob'] ? date("Y-m-d", strtotime($user['dob'])) : '';  
    } else {
        $messages[] = ['text' => "រកពុំឃើញ User ទេ!", 'type' => 'danger'];
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);

    $errorMessages = [];


    if (empty($first_name) || empty($last_name)) {
        $errorMessages[] = "First and last name cannot be empty!";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessages[] = "Invalid email format!";
    }

  
    if (isset($_FILES['img']['name']) && $_FILES['img']['name'] != "") {
        $target_dir = "../uploads/users/";
        $img = basename($_FILES["img"]["name"]);
        $target_file = $target_dir . $img;
        $check = getimagesize($_FILES["img"]["tmp_name"]);
        if ($check === false) {
            $errorMessages[] = "File is not a valid image!";
        }

        if (empty($errorMessages)) {
            move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
        }
    }


    if (empty($errorMessages)) {
        $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, gender=?, email=?, img=?, role=?, dob=? WHERE user_id=?");
        $stmt->bind_param("sssssssi", $first_name, $last_name, $gender, $email, $img, $role, $dob, $user_id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = ['text' => "User updated successfully! 🧙", 'type' => 'success'];
            echo "<script>window.location.href = 'index.php?pg=user';</script>";
        } else {
            $messages[] = ['text' => "Error: " . $stmt->error, 'type' => 'danger'];
        }
    } else {
        foreach ($errorMessages as $msg) {
            $messages[] = ['text' => $msg, 'type' => 'danger'];
        }
    }
}
?>

<div class="container shadow-sm p-3 mt-5 bg-body-tertiary rounded">
    <div class="container p-4">
        <a class="nav-link" href="index.php?pg=users">
            <i class="fas fa-chevron-left"></i>
            <span>Users</span>
        </a>

        <h2 class="text-center mb-5">កែប្រែ User</h2>
        <hr class="sidebar-divider">

        <?php if (!empty($messages)): ?>
            <?php foreach ($messages as $msg): ?>
                <div class="alert alert-<?= htmlspecialchars($msg['type']); ?>">
                    <?= htmlspecialchars($msg['text']); ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <form action="index.php?pg=edit_user&id=<?= $user_id ?>" method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="first_name">ត្រកូល</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($first_name) ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="last_name">ឈ្មោះ</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($last_name) ?>" required>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="gender">ភេទ</label>
                        <select class="form-control" id="gender" name="gender" required>
                            <option value="male" <?= ($gender == 'male') ? 'selected' : '' ?>>ប្រុស</option>
                            <option value="female" <?= ($gender == 'female') ? 'selected' : '' ?>>ស្រី</option>
                            <option value="other" <?= ($gender == 'other') ? 'selected' : '' ?>>មិនអាចកំណត់បាន</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="dob">ថ្ងៃកំណើត</label>
                        <input type="date" class="form-control" id="dob" name="dob" value="<?= htmlspecialchars($dob) ?>">
                    </div>
                </div>
                <?php 
                ?>
                <div class="col-md-6">
                    <div class="form-group">
                        
                        <label for="role">តួនាទី</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="normal" <?= ($role == 'normal') ? 'selected' : '' ?>>Normal User</option>
                            <option value="admin" <?= ($role == 'admin') ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="img">Profile Image</label>
                        <input type="file" class="form-control-file" id="img" name="img" accept="image/*" style="display: none;" onchange="previewImage(event)">
                        <?php if (!empty($img)): ?>
                            <div class="mt-2">
                                <label for="img">
                                    <img src="../database/img/users/<?= htmlspecialchars($img) ?>" alt="Profile Image" style="max-width: 200px; max-height: 200px; cursor: pointer;">
                                </label>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Update User</button>
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
