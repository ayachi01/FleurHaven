<?php

session_start();

include 'api/db.php';


$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    $passwordConfirmation = trim($_POST['passwordConfirmation']);

    if ($password !== $passwordConfirmation) {
        $error = "Passwords do not match!";
    } else {
        $stmt = $connection->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Email already exists. Please use a different email.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $connection->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $hashedPassword);

            if ($stmt->execute()) {
                $message = "Signup successful!";
            } else {
                $error = "Error: " . $stmt->error;
            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="login.css">


    <style>
        .success {
            color: green;
            font-weight: bold;
            margin: 10px 0;
            display: none; 
        }

        .error {
            color: red;
            font-weight: bold;
            margin: 10px 0;
        }

        .password-container {
            position: relative;
            width: 100%; /* Ensure it takes the full width of the form */
        }

        .password-container input {
            padding: 15px;
            font-size: 14px;
            border: 1px solid #ebebeb;
            border-radius: 5px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
            width: 100%; /* Full width of the container */
            box-sizing: border-box; /* Include padding and border in the width calculation */
        }

        .password-container .toggle-password {
            position: absolute;
            right: 15px; /* Adjusted to fit inside the input field without overlapping */
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
            font-size: 18px; /* Adjust icon size */
        }
    </style>

    <script>
        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            const type = input.type === 'password' ? 'text' : 'password';
            input.type = type;
            const icon = input.nextElementSibling;
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }
    </script>
</head>

<body>
    <div class="page">
        <div class="container">
            <div class="signup-content">
                <img src="assets/welcome-flower-img.png" alt="Login Image" class="signup-image">
                <form id="signupForm" method="POST" class="formLogin">
                    <h1> Sign Up</h1>
                    <?php if ($error): ?>
                        <div class="error"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    <?php if ($message): ?>
                        <div class="success" id="successMessage"><?php echo $message; ?></div>
                        <script>
                            document.getElementById('successMessage').style.display = 'block';
                            redirectToLogin();
                        </script>
                    <?php endif; ?>
                    
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="" autofocus="true" required>

                    <label for="password">Password</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" placeholder="" required>
                        <i class="fas fa-eye toggle-password" onclick="togglePasswordVisibility('password')"></i>
                    </div>

                    <label for="passwordConfirmation">Confirm Password</label>
                    <div class="password-container">
                        <input type="password" id="passwordConfirmation" name="passwordConfirmation" placeholder="" required>
                        <i class="fas fa-eye toggle-password" onclick="togglePasswordVisibility('passwordConfirmation')"></i>
                    </div>

                    <!-- Updated Button -->
                    <button type="submit" class="submit-btn">Sign Up</button>


                    <p>Have an account? <a href="login.php">Sign In</a></p> 
                </form>

            </div>
        </div>
    </div>
</body>
</html>