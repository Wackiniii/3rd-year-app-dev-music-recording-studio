<?php 
require_once 'core/models.php'; 
require_once 'core/handleForms.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php if (isset($_SESSION['message'])) { ?>
    <h1  class="wrap-background"><?php echo $_SESSION['message']; ?></h1>
	<?php } unset($_SESSION['message']); ?>
    
    <h1>Login Form</h1>
    <form action="core/handleForms.php" method="POST">
		<p>
			<label for="username">Username</label>
			<input type="text" name="username">
		</p>
		<p>
			<label for="password">Password</label>
			<input type="password" name="password" id="password">
            <button type="button" id="toggle" aria-pressed="false">Show</button>   
		</p>
        <p>
            <input type="submit" name="loginBtn" value="Login">
            <input type="reset" value="Reset">
        </p>
	</form>
    <p>Don't have an account yet?<a href="registration.php"> Create Account</a></p>

</body>

<script>
    const input = document.getElementById('password');
    const btn   = document.getElementById('toggle');
    btn.addEventListener('click', () => {
    const show = input.type === 'password';
    input.type = show ? 'text' : 'password';
    btn.textContent = show ? 'Hide' : 'Show';
    btn.setAttribute('aria-pressed', show);
    });   
</script>
</html>