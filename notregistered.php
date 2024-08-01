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
    <?php if ($error === 'not_registered'): ?>
        <script>
            alert('User not registered. Please register.');
            window.location.href = 'signUpPg.html';
        </script>
    <?php endif; ?>
</body>
</html>
