<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'dcfm', 3307); // Ensure the port matches your MySQL configuration
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Validate user credentials
    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();

    if ($hashedPassword && password_verify($password, $hashedPassword)) {
        // Set session variable and redirect to home page
        $_SESSION['loggedIn'] = true;
        $_SESSION['email'] = $email;
        header("Location: http://localhost/DCFM/Project/Frontend/index.html");
        exit();
    } else {
        echo "Invalid Email or Password.";
    }

    $stmt->close();
    $conn->close();
}
?>
