<?php
session_start();
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']); // Clear error message after displaying it
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
        .error { color: red; font-weight: bold; margin: 10px 0; }
        .password-container { position: relative; width: 100%; }
        .password-container input { padding: 15px; width: 100%; border: 1px solid #ebebeb; border-radius: 5px; }
        .password-container .toggle-password { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #aaa; font-size: 18px; }
    </style>
</head>

<body>
    <div class="page">
        <div class="container">
            <div class="signup-content">
                <img src="assets/welcome-flower-img.png" alt="Login Image" class="signup-image">
                <form method="POST" action="../FleurHaven/api/login_api.php" class="formLogin">
                    <h1> Welcome</h1>
                    <?php if ($error): ?>
                        <div class="error"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">Password</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" required>
                    </div>

                    <button type="submit" class="submit-btn">Sign In</button>   
                    <p>Don't have an account yet? <a href="signup.php">Sign Up</a></p> 
                </form>
            </div>
        </div>
    </div>
</body>
</html>
