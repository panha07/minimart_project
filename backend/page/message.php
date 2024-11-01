<?php if (!empty($messages)): ?>
    <div class="alert-container">
        <?php foreach ($messages as $message): ?>
            <div class="alert alert-<?= $message['type'] ?> alert-dismissible fade show" id="message" role="alert" style="display: block;">
                <?= $message['text'] ?>
               
        <?php endforeach; ?>
    </div>

    <script>
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bootstrapAlert = new bootstrap.Alert(alert);
                bootstrapAlert.close(); 
            });
        }, 3500);
    </script>
<?php endif; ?>
