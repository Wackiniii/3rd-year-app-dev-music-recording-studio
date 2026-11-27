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
		return $pdo->lastInsertId();
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
			WHERE track_id = ? AND deleted_at IS NULL
			";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$track_name, $genre, $duration_seconds, 
                            $bpm, $key_signature, $time_signature, $last_modified_by, $track_id]);

	if ($executeQuery) {
		return true;
	}
}

function softDeleteTrack($pdo, $track_id, $deleted_by) {
	$sql = "UPDATE track
			SET deleted_at = CURRENT_TIMESTAMP,
				deleted_by = ?
			WHERE track_id = ? AND deleted_at IS NULL";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$deleted_by, $track_id]);
	if ($executeQuery) {
		return $stmt->rowCount() > 0;
	}
}

function restoreTrack($pdo, $track_id) {
	$sql = "UPDATE track
			SET deleted_at = NULL,
				deleted_by = NULL
			WHERE track_id = ? AND deleted_at IS NOT NULL";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$track_id]);
	if ($executeQuery) {
		return $stmt->rowCount() > 0;
	}
}

function deleteTrackPermanently($pdo, $track_id) {
	$sql = "DELETE FROM track WHERE track_id = ? AND deleted_at IS NOT NULL";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$track_id]);
	if ($executeQuery) {
		return $stmt->rowCount() > 0;
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
					CONCAT_WS(' ', album_name, artist_name, producer_name, status, release_date, creator.username, modifier.username)
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
				images.images_name AS images_name,
				track.track_name AS track_name,
				track.genre AS genre,
				track.duration_seconds AS duration_seconds,
				track.bpm AS bpm,
                track.key_signature AS key_signature,
                track.time_signature AS time_signature,                
				creator.username AS added_by,
				modifier.username AS last_modified_by,
				track.date_added AS date_added,
				track.date_modified AS date_modified
			FROM track
			JOIN album ON track.album_id = album.album_id
			LEFT JOIN users AS creator ON track.added_by = creator.user_id
            LEFT JOIN users AS modifier ON track.last_modified_by = modifier.user_id
			LEFT JOIN images ON images.track_id = track.track_id
			WHERE track.album_id = ? AND track.deleted_at IS NULL
			ORDER BY track.track_name ASC
			";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$album_id]);
	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getAllDeletedTracks($pdo) {
	$sql = "SELECT
				track.track_id,
				track.track_name,
				track.genre,
				track.deleted_at,
				album.album_name,
				deleter.username AS deleted_by
			FROM track
			JOIN album ON track.album_id = album.album_id
			LEFT JOIN users AS deleter ON track.deleted_by = deleter.user_id
			WHERE track.deleted_at IS NOT NULL
			ORDER BY track.deleted_at DESC";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();
	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getTrackByID($pdo, $track_id) {
	$sql = "SELECT 
				track.track_id AS track_id,
				images.images_id AS images_id,
				images.images_name AS images_name,
				images.description AS image_description,
				track.track_name AS track_name,
				track.genre AS genre,
				track.duration_seconds AS duration_seconds,
                track.bpm AS bpm,
                track.key_signature AS key_signature,
                track.time_signature AS time_signature,
                track.date_added AS date_added,
				creator.username AS added_by,
				modifier.username AS last_modified_by
			FROM track
			LEFT JOIN users AS creator ON track.added_by = creator.user_id
            LEFT JOIN users AS modifier ON track.last_modified_by = modifier.user_id
			LEFT JOIN images ON images.track_id = track.track_id
			WHERE track.track_id = ? AND track.deleted_at IS NULL
			ORDER BY track.track_name ASC
			";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$track_id]);
	if ($executeQuery) {
		return $stmt->fetch();
	}
}
function searchTrack($pdo, $album_id, $searchQuery) {
	$sql = "SELECT 
				track.track_id AS track_id,
				track.track_name AS track_name,
 				track.genre AS genre,
 				track.duration_seconds AS duration_seconds,
				track.bpm AS bpm,
                track.key_signature AS key_signature,
                track.time_signature AS time_signature,                
				creator.username AS added_by,
				modifier.username AS last_modified_by,
				track.date_added AS date_added,
				track.date_modified AS date_modified
			FROM track
			JOIN album ON track.album_id = album.album_id
 			LEFT JOIN users AS creator ON track.added_by = creator.user_id
            LEFT JOIN users AS modifier ON track.last_modified_by = modifier.user_id
 			WHERE track.album_id = ? 
				AND track.deleted_at IS NULL
				AND CONCAT_WS(' ', track_name, genre, duration_seconds, bpm, key_signature, time_signature, artist_name, producer_name, creator.username, modifier.username)
 			LIKE ?
			ORDER BY track.track_name ASC";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$album_id, "%" . $searchQuery . "%"]);
	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

// ===================================================================================

function insertImages($pdo, $images_name, $username, $description, $images_id=null, $track_id=null) {
	if (empty($images_id)) {
		$sql = "INSERT INTO images (images_name, username, description, track_id) VALUES (?,?,?,?)";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$images_name, $username, $description, $track_id]);

		if ($executeQuery) {
			return true;
		}
	}
	else {
		$sql = "UPDATE images SET images_name = ?, description = ?, track_id = ? WHERE images_id = ?";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$images_name, $description, $track_id, $images_id]);

		if ($executeQuery) {
			return true;
		}
	}
}

function deleteImages($pdo, $images_id) {
	$sql = "DELETE FROM images WHERE images_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$images_id]);

	if ($executeQuery) {
		return true;
	}
}

function getAllImages($pdo, $username=null) {
	if (empty($username)) {
		$sql = "SELECT * from images ORDER BY date_added DESC";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute();

		if ($executeQuery) {
			return $stmt->fetchAll();
		}
	}
	else {
		$sql = "SELECT * FROM images WHERE username = ? ORDER BY date_added DESC";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$username]);

		if ($executeQuery) {
			return $stmt->fetchAll();
		}
	}
}

function getImagesByID($pdo, $images_id) {
	$sql = "SELECT * FROM images WHERE images_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$images_id]);

	if ($executeQuery) {
		return $stmt->fetch();
	}
}