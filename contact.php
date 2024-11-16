<?php
// Database configuration
$host = 'localhost';
$dbname = 'portfolio';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = htmlspecialchars(trim($_POST['full_name'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $mobile = htmlspecialchars(trim($_POST['mobile'] ?? ''));
    $subject = htmlspecialchars(trim($_POST['subject'] ?? ''));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));

    if (!empty($fullName) && !empty($email) && !empty($mobile) && !empty($subject) && !empty($message)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO contacts (full_name, email, mobile, subject, message)
                VALUES (:full_name, :email, :mobile, :subject, :message)
            ");
            $stmt->execute([
                ':full_name' => $fullName,
                ':email' => $email,
                ':mobile' => $mobile,
                ':subject' => $subject,
                ':message' => $message
            ]);

            // Redirect to the thank-you page
            header("Location: thankyou.html");
            exit();
        } catch (PDOException $e) {
            echo "Failed to save the message: " . $e->getMessage();
        }
    } else {
        echo "All fields are required.";
    }
} else {
    echo "Invalid request method.";
}
?>
