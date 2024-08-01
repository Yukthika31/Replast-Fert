<?php
// Database connection parameters
$servername = "localhost";
$username = "root"; // Change this if your MySQL username is different
$password = ""; // Change this if your MySQL password is different
$dbname = "projectdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$product = $_POST['product'];
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$quantity = $_POST['quantity'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO order_details (product, name, email, phone, quantity) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $product, $name, $email, $phone, $quantity);

// Execute the statement
if ($stmt->execute()) {
    echo "<script>alert('Ordered successful!');</script>";
    header("Location: aboutUs.html") ;
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
