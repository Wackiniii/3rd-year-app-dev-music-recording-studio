<?php 

session_start();
require_once 'dbConfig.php'; 
require_once 'models.php';
require_once 'validation.php';

if (isset($_POST['registerBtn'])) {
	$username = sanitizeInput($_POST['username']);
	$password = $_POST['password'];
	$confirm_password = $_POST['confirm_password'];
	$first_name = sanitizeInput($_POST['first_name']);
	$last_name = sanitizeInput($_POST['last_name']);
	$birth_date = sanitizeInput($_POST['birth_date']);
	$gender = sanitizeInput($_POST['gender']);

	$_SESSION['old'] = [
        'username'   => $username,
        'first_name' => $first_name,
        'last_name'  => $last_name,
        'birth_date' => $birth_date,
        'gender'     => $gender
    ];

	if (empty($username) || empty($password) || empty($confirm_password) 
		|| empty($first_name) || empty($last_name) || empty($birth_date) 
		|| empty($gender)) {
			$_SESSION['message'] = "Please make sure the input fields 
			are not empty for registration!";
			header("Location: ../registration.php");
			exit;
	}
	if ($password !== $confirm_password) {
		$_SESSION['message'] = "Please check if both passwords are equal!";
		header("Location: ../registration.php");
		exit;
	}
	if (validatePassword($password)) {
		$query = registerUser($pdo, $username, $password, $first_name,
		$last_name, $birth_date, $gender);

		if ($query) {
			unset($_SESSION['old']);
			header("Location: ../login.php");
			exit;
		}
		else {
			header("Location: ../registration.php");
			exit;
		}
	} else {
		$_SESSION['message'] = "Password should be more than 8 characters and should contain both uppercase, lowercase, and numbers";
		header("Location: ../registration.php");
		exit;
	}
} 

if (isset($_POST['loginBtn'])) {

	$username = sanitizeInput($_POST['username']);
	$password = $_POST['password'];	

	if (empty($username) && empty($password)) {
		$_SESSION['message'] = "Please make sure the input fields 
		are not empty for the login!";
		header("Location: ../login.php");
	}
	
    $query = loginUser($pdo, $username, $password);
    if ($query) {
        $_SESSION['user_id'] = $query;
        header("Location: ../index.php");
		exit;
    } else {
		$_SESSION['message'] = "Invalid credentials";
		header("Location: ../login.php");
		exit;
	}
} 

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['insertAlbumBtn'])) {
	$added_by = $_SESSION['user_id'];
	$query = insertAlbum($pdo, $_POST['album_name'], $_POST['artist_name'], 
		$_POST['producer_name'], $_POST['status'], $_POST['release_date'], $added_by);

	if ($query) {
		header("Location: ../index.php");
		exit;
	}
	else {
		echo "Insertion failed";
	}

}

if (isset($_POST['editAlbumBtn'])) {
	$last_modified_by = $_SESSION['user_id'];
	$query = updateAlbum($pdo, $_POST['album_name'], $_POST['artist_name'], 
		$_POST['producer_name'], $_POST['status'], $_POST['release_date'], 
		$last_modified_by, $_GET['album_id']);

	if ($query) {
		header("Location: ../index.php");
		exit;
	}

	else {
		echo "Edit failed";
	}

}

if (isset($_POST['deleteAlbumBtn'])) {
	$query = deleteAlbum($pdo, $_GET['album_id']);

	if ($query) {
		header("Location: ../index.php");
		exit;
	}

	else {
		echo "Deletion failed";
	}
}

if (isset($_POST['insertNewTrackBtn'])) {
	$added_by = $_SESSION['user_id'];
	$query = insertTrack($pdo, $_POST['track_name'], 
            $_POST['genre'], $_POST['duration_seconds'] ,
            $_POST['bpm'], $_POST['key_signature'], 
            $_POST['time_signature'], $added_by, $_GET['album_id']);

	if ($query) {
		header("Location: ../viewTracks.php?album_id=" .$_GET['album_id']);
		exit;
	}
	else {
		echo "Insertion failed";
	}
}

if (isset($_POST['editTrackBtn'])) {
	$last_modified_by = $_SESSION['user_id'];
	$query = updateTrack($pdo, $_POST['track_name'], $_POST['genre'], 
            $_POST['duration_seconds'], $_POST['bpm'], $_POST['key_signature'], 
            $_POST['time_signature'], $last_modified_by, $_GET['track_id']);

	if ($query) {
		header("Location: ../viewTracks.php?album_id=" .$_GET['album_id']);
	}
	else {
		echo "Update failed";
	}

}

if (isset($_POST['deleteTrackBtn'])) {
	$query = deleteTrack($pdo, $_GET['track_id']);

	if ($query) {
		header("Location: ../viewTracks.php?album_id=" .$_GET['album_id']);
		exit;
	}
	else {
		echo "Deletion failed";
	}
}




?>