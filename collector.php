<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        // Retrieve form data
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $city = $_POST['city'] ?? '';
        $district = $_POST['district'] ?? '';
        $state = $_POST['state'] ?? '';
        $pincode = $_POST['pincode'] ?? '';
        $place = $_POST['place_name'] ?? '';
        $amount = $_POST['amount'] ?? '';

        // Debug: Print form data
        echo '<pre>';
        print_r($_POST);
        echo '</pre>';

        // Handle file upload
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['photo']['tmp_name'];
            $fileName = $_FILES['photo']['name'];
            $fileSize = $_FILES['photo']['size'];
            $fileType = $_FILES['photo']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Define allowed file extensions
            $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($fileExtension, $allowedExts)) {
                // Create new filename and move file
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = './uploaded_files/';
                
                // Ensure the upload directory exists
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }

                $dest_path = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    echo 'File is successfully uploaded.<br>';

                    // Database connection parameters
                    $host = 'localhost';
                    $user = 'root';
                    $pass = '';
                    $dbname = 'projectdb';

                    // Create connection
                    $conn = mysqli_connect($host, $user, $pass, $dbname);

                    // Check connection
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    // SQL query with prepared statement
                    $sql = "INSERT INTO informer (Name, Phone_Number, City, District, State, Pincode, Place, Amount, Image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    // Prepare the statement
                    $stmt = mysqli_prepare($conn, $sql);

                    // Check if prepare statement succeeded
                    if ($stmt === false) {
                        die('MySQL prepare error: ' . mysqli_error($conn));
                    }

                    // Bind parameters
                    mysqli_stmt_bind_param($stmt, "sisssisis", $name, $phone, $city, $district, $state, $pincode, $place, $amount, $newFileName);

                    // Execute statement
                    if (mysqli_stmt_execute($stmt)) {
                        echo "<script>alert('Registration successful!');</script>";
                        header("Location: recycle.html");
                        exit;
                    } else {
                        echo "Error executing statement: " . mysqli_stmt_error($stmt);
                        error_log("SQL Error: " . mysqli_stmt_error($stmt));
                    }

                    // Close statement
                    mysqli_stmt_close($stmt);

                    // Close the connection
                    mysqli_close($conn);
                } else {
                    echo 'Error moving the uploaded file.';
                }
            } else {
                echo 'Unsupported file type.';
            }
        } else {
            echo 'No file uploaded or file upload error.';
        }
    }
}
?>
