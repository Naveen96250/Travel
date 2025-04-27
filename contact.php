<?php
session_start();

// Database connection
$db_hostname = "127.0.0.1";
$db_username = "root";
$db_password = "";
$db_name = "travels";

try {
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
  $conn = new mysqli($db_hostname, $db_username, $db_password, $db_name);

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Email required check
  if (empty($email)) {
    $_SESSION['toast'] = [
      'status' => 'error',
      'message' => 'Email is required for response!'
    ];
    header("Location: index.php");
    exit();
  }

    $sql = "INSERT INTO users(name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);
    $stmt->execute();

    $_SESSION['toast'] = [
      'status' => 'success',
      'message' => 'We will contact you soon!'
    ];
  }

} catch (mysqli_sql_exception $e) {
  $_SESSION['toast'] = [
    'status' => 'error',
    'message' => 'Your Response already submitted!'
  ];
} finally {
  if (isset($conn)) {
    $conn->close();
  }

  // Redirect back to index.php after handling
  header("Location: index.php");
  exit();
}
?>
