<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    $passwordConfirmation = trim($_POST['passwordConfirmation']);

    if ($password !== $passwordConfirmation) {
        $response['message'] = "Passwords do not match!";
    } else {
        $stmt = $connection->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $response['message'] = "Email already exists. Please use a different email.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $connection->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $hashedPassword);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = "Signup successful!";
            } else {
                $response['message'] = "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}

echo json_encode($response);
?>
