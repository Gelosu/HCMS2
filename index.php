
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Platypi:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="index.css">
    <title>SMHCMS</title>
</head>

<body>
    <header>
        <h1>BRGY STA. MARIA  HEALTH CENTER </h1>
    </header>

    <main>
        <div class="welcome-container">
            <h2>Welcome to Health Center Managment System!</h2>
            <a href="#" id="openModalBtn" class="welcome-button">Get Started</a>
        </div>
    </main>

    <!-- Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeModalBtn">&times;</span>
            <h2>Login</h2>
            <!-- Your login form goes here -->
            <form action="login.php" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter your username" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter your password" required>

    <label>
        <input type="checkbox" id="rememberMe" name="rememberMe">
        Remember Me
    </label>
    <p>Login as admin? <a href="adminlogin.php">click here</a></p>
    <input type="submit" name="login" value="Login">
</form>

        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> STA.MARIA HCMS. All rights reserved.</p>
    </footer>

    <script>
        // Get the modal and buttons
        var modal = document.getElementById('loginModal');
        var openModalBtn = document.getElementById('openModalBtn');
        var closeModalBtn = document.getElementById('closeModalBtn');

        // Open the modal
        openModalBtn.onclick = function() {
            modal.style.display = 'block';
        }

        // Close the modal
        closeModalBtn.onclick = function() {
            modal.style.display = 'none';
        }

        // Close the modal if the user clicks outside the modal
        window.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>

</html>
