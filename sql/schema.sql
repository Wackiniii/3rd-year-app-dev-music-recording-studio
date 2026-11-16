CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    birth_date DATE,
    gender VARCHAR(20),
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE album (
    album_id INT AUTO_INCREMENT PRIMARY KEY,
    album_name VARCHAR(50) NOT NULL,
    artist_name VARCHAR(100) NOT NULL,
    producer_name VARCHAR(100) NOT NULL,
    status VARCHAR(50) DEFAULT 'Recording',
    release_date DATE,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    added_by INT NOT NULL,
    last_modified_by INT,

    FOREIGN KEY (added_by) REFERENCES users(user_id) ON DELETE RESTRICT,
    FOREIGN KEY (last_modified_by) REFERENCES users(user_id) ON DELETE SET NULL
);

CREATE TABLE track (
    track_id INT AUTO_INCREMENT PRIMARY KEY,
    album_id INT NOT NULL,
    track_name VARCHAR(100) NOT NULL,
    genre VARCHAR(50) NOT NULL,
    duration_seconds INT,
    bpm INT,
    key_signature VARCHAR(10),
    time_signature VARCHAR(10),
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

    added_by INT NOT NULL,
    last_modified_by INT,

    FOREIGN KEY (album_id) REFERENCES album(album_id) ON DELETE CASCADE,
    FOREIGN KEY (added_by) REFERENCES users(user_id) ON DELETE RESTRICT,
    FOREIGN KEY (last_modified_by) REFERENCES users(user_id) ON DELETE SET NULL
);

