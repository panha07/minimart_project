<?php
// Assuming the database connection is already established
$sql = "SELECT * FROM brands";
$result = mysqli_query($conn, $sql);

function displayBrands($result) {
    if (mysqli_num_rows($result) > 0) {
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered table-hover table-striped">';
        echo '<thead class="thead-dark">
                <tr class="text-center">
                    <th scope="col">លេខរៀង</th>
                    <th scope="col">ឈ្មោះម៉ាក</th>
                    <th scope="col">កាលបរិច្ឆេទបង្កើត</th>
                    <th scope="col">កាលបរិច្ឆេទកែប្រែ</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>';
        echo '<tbody>';
        $i = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr class="text-center">';
            echo '<td>' . $i++ . '</td>';
            echo '<td>' . htmlspecialchars($row['brand_name']) . '</td>';
            echo '<td>' . ($row['create_date'] ? date('Y-m-d H:i:s', strtotime($row['create_date'])) : 'N/A') . '</td>';
            echo '<td>' . ($row['update_date'] ? date('Y-m-d H:i:s', strtotime($row['update_date'])) : 'N/A') . '</td>';
            echo '<td>
                    <div class="btn-group" role="group" aria-label="Brand Actions">
                        <a href="index.php?pg=edit_brand&id=' . $row['brand_id'] . '" class="btn btn-warning btn-sm" title="Edit Brand"><i class="bi bi-pencil-fill"></i></a>
                        <a href="javascript:void(0);" class="btn btn-danger btn-sm" title="Delete Brand" onclick="confirmDelete(' . $row['brand_id'] . ')"><i class="bi bi-trash-fill"></i></a>
                    </div>
                  </td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        echo '<p class="text-center">
        </br>
        ពុំមាន Brand ទេ 🤷</p>';
    }
}
?>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-center">គ្រប់គ្រងម៉ាកទំនិញ</h2>
        <a href="index.php?pg=add_brand" class="btn btn-primary">បន្ថែមម៉ាក</a>
    </div>

    <ul class="nav nav-tabs" id="brandTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="#brands" data-toggle="tab">គ្រប់ម៉ាក</a>
        </li>
    </ul>

    <div class="tab-content" id="brandTabsContent">
        <div class="tab-pane fade show active" id="brands" role="tabpanel">
            <?php displayBrands($result); ?>
        </div>
    </div>
</div>

<script>
    function confirmDelete(brandId) {
        if (confirm("Do you want to delete this brand?")) {
            window.location.href = 'index.php?pg=delete_brand&id=' + brandId;
        }
    }
</script>
