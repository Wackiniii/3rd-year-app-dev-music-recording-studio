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

// ===================================================================================

if (isset($_POST['insertAlbumBtn'])) {
	$album_name = sanitizeInput($_POST['album_name']);
	$artist_name = sanitizeInput($_POST['artist_name']);
	$producer_name = sanitizeInput($_POST['producer_name']);
	$status = sanitizeInput($_POST['status']);
	$release_date = sanitizeInput($_POST['release_date']);

	if (empty($album_name) || empty($artist_name) || empty($producer_name)) {
		$_SESSION['message'] = "Make sure input fields containing * are not empty!";
		header("Location: ../index.php");
		exit;
	} 

	$added_by = $_SESSION['user_id'];
	$query = insertAlbum($pdo, $album_name, $artist_name, $producer_name, $status, $release_date, $added_by);

	if ($query) {
		header("Location: ../index.php");
		exit;
	}
	else {
		echo "Insertion failed";
	}
}

if (isset($_POST['editAlbumBtn'])) {
	$album_name = sanitizeInput($_POST['album_name']);
	$artist_name = sanitizeInput($_POST['artist_name']);
	$producer_name = sanitizeInput($_POST['producer_name']);
	$status = sanitizeInput($_POST['status']);
	$release_date = sanitizeInput($_POST['release_date']);

	if (empty($album_name) || empty($artist_name) || empty($producer_name)) {
		$_SESSION['message'] = "Make sure input fields containing * are not empty!";
		header("Location: ../index.php");
		exit;
	} 

	$last_modified_by = $_SESSION['user_id'];
	$query = updateAlbum($pdo, $album_name, $artist_name, $producer_name, $status, $release_date, 
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

// ===================================================================================

if (isset($_POST['insertNewTrackBtn'])) {
	$track_name = sanitizeInput($_POST['track_name']);
	$genre = sanitizeInput($_POST['genre']);
	$duration_seconds = sanitizeInput($_POST['duration_seconds']);
	$bpm = sanitizeInput($_POST['bpm']);
	$key_signature = sanitizeInput($_POST['key_signature']);
	$time_signature = sanitizeInput($_POST['time_signature']);

	if (empty($track_name) || empty($genre)) {
		$_SESSION['message'] = "Make sure input fields containing * are not empty!";
		header("Location: ../viewTracks.php?album_id=" .$_GET['album_id']);
		exit;
	} 
	
	$added_by = $_SESSION['user_id'];
	$query = insertTrack($pdo, $track_name, $genre, $duration_seconds,
			$bpm, $key_signature, $time_signature, $added_by, $_GET['album_id']);

	if ($query) {
		header("Location: ../viewTracks.php?album_id=" .$_GET['album_id']);
		exit;
	}
	else {
		echo "Insertion failed";
	}
}

if (isset($_POST['editTrackBtn'])) {
	$track_name = sanitizeInput($_POST['track_name']);
	$genre = sanitizeInput($_POST['genre']);
	$duration_seconds = sanitizeInput($_POST['duration_seconds']);
	$bpm = sanitizeInput($_POST['bpm']);
	$key_signature = sanitizeInput($_POST['key_signature']);
	$time_signature = sanitizeInput($_POST['time_signature']);

	if (empty($track_name) || empty($genre)) {
		$_SESSION['message'] = "Make sure input fields containing * are not empty!";
		header("Location: ../viewTracks.php?album_id=" .$_GET['album_id']);
		exit;
	} 

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
	if (!isset($_SESSION['user_id'])) {
		header("Location: ../login.php");
		exit;
	}

	$query = softDeleteTrack($pdo, $_GET['track_id'], $_SESSION['user_id']);

	if ($query) {
		$_SESSION['message'] = "Track moved to trash.";
		header("Location: ../viewTracks.php?album_id=" .$_GET['album_id']);
		exit;
	}
	else {
		$_SESSION['message'] = "Track could not be moved to trash.";
		header("Location: ../viewTracks.php?album_id=" .$_GET['album_id']);
		exit;
	}
}

if (isset($_POST['restoreTrackBtn'])) {
	if (!isset($_SESSION['user_id'])) {
		header("Location: ../login.php");
		exit;
	}

	$query = restoreTrack($pdo, $_GET['track_id']);
	$_SESSION['message'] = $query ? "Track restored." : "Track could not be restored.";
	header("Location: ../viewTrash.php");
	exit;
}

if (isset($_POST['deleteTrackPermanentlyBtn'])) {
	if (!isset($_SESSION['user_id'])) {
		header("Location: ../login.php");
		exit;
	}

	$query = deleteTrackPermanently($pdo, $_GET['track_id']);
	$_SESSION['message'] = $query
		? "Track permanently deleted."
		: "Track could not be permanently deleted.";
	header("Location: ../viewTrash.php");
	exit;
}




?>