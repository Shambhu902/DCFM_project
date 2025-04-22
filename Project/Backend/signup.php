<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "POST request received.";
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'dcfm', 3307); // Added port 3307
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check for duplicate email
    $checkEmailStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    if (!$checkEmailStmt) {
        die("Prepare failed: " . $conn->error);
    }
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();

    if ($checkEmailStmt->num_rows > 0) {
        echo "Error: Email already exists.";
        $checkEmailStmt->close();
        $conn->close();
        exit;
    }
    $checkEmailStmt->close();

    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, created_at) VALUES (?, ?, ?, NOW())");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sss", $fullName, $email, $password);

    if ($stmt->execute()) {
        // Redirect to the home page
        header("Location: http://localhost/DCFM/Project/Frontend/index.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(405); // Set HTTP status code to 405
    echo "Method Not Allowed";
    exit;
}
?>
