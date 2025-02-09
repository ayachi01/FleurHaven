<?php
session_start();

include 'api/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    $stmt = $connection->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['email'] = $email;
            header("Location: welcome-page.html");    
            exit();
        } else {
            $error = "Invalid password. Please try again.";
        }
    } else {
        $error = "No user found with that email address.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const passwordType = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = passwordType;
            const icon = document.getElementById('togglePasswordIcon');
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
                <form method="POST" action="login.php" class="formLogin">
                    <h1> Welcome</h1>
                    <?php if ($error): ?>
                        <div class="error"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="" autofocus="true" required>

                    <label for="password">Password</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" placeholder="" required>
                        <i id="togglePasswordIcon" class="fas fa-eye toggle-password" onclick="togglePasswordVisibility()"></i>
                    </div>

                    <button type="submit" class="submit-btn">Sign In</button>   

                    <p>Don't have an account yet? <a href="signup.php">Sign Up</a></p> 
                </form>
            </div>
        </div>
    </div>
</body>
</html>