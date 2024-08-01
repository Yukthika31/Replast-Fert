<?php
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
</head>
<body>
    <?php if ($error === 'duplicate_entry'): ?>
        <script>
            alert('User already registered. Please Login.');
            window.location.href = 'loginPg.html';
        </script>
    <?php endif; ?>
</body>
</html>
