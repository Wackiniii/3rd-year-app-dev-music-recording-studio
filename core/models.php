<?php  
function registerUser($pdo , $username, $password, $first_name, $last_name, $birth_date, $gender) {
	$username = trim((string)$username);
	$first_name = trim((string)$first_name);
	$last_name = trim((string)$last_name);
	$birth_date = trim((string)$birth_date);
	$gender = trim((string)$gender);

	$checkUserSql = "SELECT * FROM users WHERE username = ?";
	$checkUserStmt = $pdo->prepare($checkUserSql);
	$checkUserStmt->execute([$username]);

	if ($checkUserStmt->fetch()) {
		$_SESSION['message'] = "Username is already taken.";
		return false;
	}

	$hashed_password = password_hash($password, PASSWORD_ARGON2ID);

	$sql = "INSERT INTO users (username, password, first_name, last_name, birth_date, gender) 
            VALUES (?, ?, ?, ?, ?, ?)";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$username, $hashed_password, $first_name, 
		$last_name, $birth_date, $gender]);

	if ($executeQuery) {
		return true;
	}
}

function loginUser($pdo , $username, $password) {
	$sql = "SELECT * FROM users WHERE username=?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([trim((string)$username)]);

	$user = $stmt->fetch();
	if ($user && password_verify($password, $user['password'])) {
        return $user['user_id'];
    }
    return false;
}

// ===================================================================================
function insertAlbum($pdo, $album_name, $artist_name, $producer_name, 
	$status, $release_date, $added_by) {

    if (trim((string)$status) === '') {
        $status = 'Recording';
    }
    if (trim((string)$release_date) === '') {
        $release_date = null;
    }

	$sql = "INSERT INTO album(album_name, artist_name, producer_name, 
		status, release_date, added_by) VALUES(?,?,?,?,?,?)";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$album_name, $artist_name, $producer_name, 
		$status, $release_date, $added_by]);

	if ($executeQuery) {
		return true;
	}
}

function updateAlbum($pdo, $album_name, $artist_name, $producer_name, 
	$status, $release_date, $last_modified_by, $album_id) {

	if (trim((string)$status) === '') {
        $status = 'Recording';
    }
    if (trim((string)$release_date) === '') {
        $release_date = null;
    }

	$sql = "UPDATE album
				SET album_name = ?,
					artist_name = ?,
					producer_name = ?, 
					status = ?,
                    release_date = ?,
					last_modified_by = ?
				WHERE album_id = ?
			";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$album_name, $artist_name, 
            $producer_name, $status, $release_date, $last_modified_by, $album_id]);
	
	if ($executeQuery) {
		return true;
	}

}


function deleteAlbum($pdo, $album_id) {
		$sql = "DELETE FROM album WHERE album_id = ?";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$album_id]);

		if ($executeQuery) {
			return true;
		}
}
// ===================================================================================

function insertTrack($pdo, $track_name, $genre, $duration_seconds, 
                    $bpm, $key_signature, $time_signature, $added_by, $album_id) {

    if (trim((string)$duration_seconds) === '') {
        $duration_seconds = null;
    }
    if (trim((string)$bpm) === '') {
        $bpm = null;
    }
	if (trim((string)$key_signature) === '') {
        $key_signature = null;
    }
	if (trim((string)$time_signature) === '') {
		$time_signature = null;
	}
	
	$sql = "INSERT INTO track (track_name, genre, duration_seconds, 
                    bpm, key_signature, time_signature, added_by, album_id) VALUES (?,?,?,?,?,?,?,?)";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$track_name, $genre, $duration_seconds, 
                                $bpm, $key_signature, $time_signature, $added_by, $album_id]);
	if ($executeQuery) {
		return true;
	}

}

function updateTrack($pdo, $track_name, $genre, $duration_seconds, 
                    $bpm, $key_signature, $time_signature, $last_modified_by, $track_id) {
	if (trim((string)$duration_seconds) === '') {
        $duration_seconds = null;
    }
    if (trim((string)$bpm) === '') {
        $bpm = null;
    }
	if (trim((string)$key_signature) === '') {
        $key_signature = null;
    }
	if (trim((string)$time_signature) === '') {
		$time_signature = null;
	}

	$sql = "UPDATE track
			SET track_name = ?,
				genre = ?,
                duration_seconds = ?,
                bpm = ?,
                key_signature = ?,
                time_signature = ?,
				last_modified_by = ?
			WHERE track_id = ?
			";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$track_name, $genre, $duration_seconds, 
                            $bpm, $key_signature, $time_signature, $last_modified_by, $track_id]);

	if ($executeQuery) {
		return true;
	}
}

function deleteTrack($pdo, $track_id) {
	$sql = "DELETE FROM track WHERE track_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$track_id]);
	if ($executeQuery) {
		return true;
	}
}

// ===================================================================================

function getAllAlbum($pdo) {
	$sql = "SELECT 
				album.*,
				creator.username AS added_by,
				modifier.username AS last_modified_by 
			FROM album
			LEFT JOIN users AS creator ON album.added_by = creator.user_id
			LEFT JOIN users AS modifier ON album.last_modified_by = modifier.user_id
			";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getAlbumByID($pdo, $album_id) {
	$sql = "SELECT 
				album.*,
				creator.username AS added_by,
				modifier.username AS last_modified_by 
			FROM album 
			LEFT JOIN users AS creator ON album.added_by = creator.user_id
			LEFT JOIN users AS modifier ON album.last_modified_by = modifier.user_id
			WHERE album_id = ?
			";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$album_id]);

	if ($executeQuery) {
		return $stmt->fetch();
	}
}

function searchAlbum($pdo, $searchQuery) {
	$sql = "SELECT album.*, creator.username AS added_by,
					modifier.username AS last_modified_by  
					FROM album 
					LEFT JOIN users AS creator ON album.added_by = creator.user_id
					LEFT JOIN users AS modifier ON album.last_modified_by = modifier.user_id
					WHERE
					CONCAT(album_name, artist_name, producer_name, status, release_date)
					LIKE ?";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute(["%" . $searchQuery . "%"]);
	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

// ===================================================================================

function getTracksByAlbum($pdo, $album_id) {
	
	$sql = "SELECT 
				track.track_id AS track_id,
				track.track_name AS track_name,
				track.genre AS genre,
				track.duration_seconds AS duration_seconds,
				track.bpm AS bpm,
                track.key_signature AS key_signature,
                track.time_signature AS time_signature,                
				album.artist_name AS artist_name,
                album.producer_name AS producer_name,
				creator.username AS added_by,
				modifier.username AS last_modified_by,
				track.date_added AS date_added
			FROM track
			JOIN album ON track.album_id = album.album_id
			LEFT JOIN users AS creator ON track.added_by = creator.user_id
            LEFT JOIN users AS modifier ON track.last_modified_by = modifier.user_id
			WHERE track.album_id = ?
			ORDER BY track.track_name ASC
			";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$album_id]);
	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getTrackByID($pdo, $track_id) {
	$sql = "SELECT 
				track.track_id AS track_id,
				track.track_name AS track_name,
				track.genre AS genre,
				track.duration_seconds AS duration_seconds,
                track.bpm AS bpm,
                track.key_signature AS key_signature,
                track.time_signature AS time_signature,
                track.date_added AS date_added,
				album.artist_name AS artist_name,
                album.producer_name AS producer_name,
				creator.username AS added_by,
				modifier.username AS last_modified_by
			FROM track
			JOIN album ON track.album_id = album.album_id
			LEFT JOIN users AS creator ON track.added_by = creator.user_id
            LEFT JOIN users AS modifier ON track.last_modified_by = modifier.user_id
			WHERE track.track_id  = ? 
			ORDER BY track.track_name ASC
			";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$track_id]);
	if ($executeQuery) {
		return $stmt->fetch();
	}
}

?>