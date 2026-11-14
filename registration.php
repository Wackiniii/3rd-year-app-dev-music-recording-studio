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
    <h1>Registration Form</h1>
    <form action="core/handleForms.php" method="POST">
		<p>
			<label for="username">Username</label>
			<input type="text" name="username" value="<?php echo htmlspecialchars($_SESSION['old']['username'] ?? ''); ?>">
		</p>
        <p>
			<label for="first_name">First Name</label>
			<input type="text" name="first_name" value="<?php echo htmlspecialchars($_SESSION['old']['first_name'] ?? ''); ?>">
		</p>
        <p>
			<label for="last_name">Last Name</label>
			<input type="text" name="last_name" value="<?php echo htmlspecialchars($_SESSION['old']['last_name'] ?? ''); ?>">
		</p>
        <p>
			<label for="birth_date">Birth Date</label>
			<input type="date" name="birth_date" value="<?php echo htmlspecialchars($_SESSION['old']['birth_date'] ?? ''); ?>">
		</p>
        <p>
			<label for="gender">Gender</label>
			<input type="text" name="gender" value="<?php echo htmlspecialchars($_SESSION['old']['gender'] ?? ''); ?>">
		</p>
		<p>
			<label for="password">Password</label>
			<input type="password" name="password" id="password">
		</p>
        <p>
            <label for="confirm_password">Confirm Password</label>
			<input type="confirm_password" name="confirm_password" id="confirm_password">  
        </p>
        <p>
            <input type="submit" name="registerBtn">
            <input type="reset" value="Reset">
        </p>
        <?php 
        unset($_SESSION['old']); 
        ?>
	</form>
    <p>Already have an account?<a href="login.php"> Login Account</a></p>

    <?php if (isset($_SESSION['message'])) { ?>
		<h1 style="color: green;"><?php echo $_SESSION['message']; ?></h1>
	<?php } unset($_SESSION['message']); ?>
</body>
</html>