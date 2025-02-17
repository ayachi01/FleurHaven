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
            width: 100%;
        }

        .password-container input {
            padding: 15px;
            font-size: 14px;
            border: 1px solid #ebebeb;
            border-radius: 5px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
            width: 100%;
            box-sizing: border-box;
        }

        .password-container .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
            font-size: 18px;
        }
    </style>

    <script>
        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            input.type = input.type === 'password' ? 'text' : 'password';
            input.nextElementSibling.classList.toggle('fa-eye');
            input.nextElementSibling.classList.toggle('fa-eye-slash');
        }

        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("signupForm").addEventListener("submit", function (event) {
                event.preventDefault(); 

                let formData = new FormData(this);

                fetch("api/signup_api.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    let messageBox = document.getElementById("messageBox");
                    messageBox.style.display = "block";
                    messageBox.innerHTML = data.message;
                    messageBox.style.color = data.success ? "green" : "red";

                    if (data.success) {
                        setTimeout(() => {
                            window.location.href = "login.php";
                        }, 2000);
                    }
                })
                .catch(error => console.error("Error:", error));
            });
        });
    </script>
</head>

<body>
    <div class="page">
        <div class="container">
            <div class="signup-content">
                <img src="assets/welcome-flower-img.png" alt="Login Image" class="signup-image">
                <form id="signupForm" class="formLogin">
                    <h1>Sign Up</h1>
                    <div id="messageBox" class="success" style="display: none;"></div>
                    
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">Password</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" required>
                        <i class="fas fa-eye toggle-password" onclick="togglePasswordVisibility('password')"></i>
                    </div>

                    <label for="passwordConfirmation">Confirm Password</label>
                    <div class="password-container">
                        <input type="password" id="passwordConfirmation" name="passwordConfirmation" required>
                        <i class="fas fa-eye toggle-password" onclick="togglePasswordVisibility('passwordConfirmation')"></i>
                    </div>

                    <button type="submit" class="submit-btn">Sign Up</button>

                    <p>Have an account? <a href="login.php">Sign In</a></p> 
                </form>
            </div>
        </div>
    </div>
</body>
</html>
