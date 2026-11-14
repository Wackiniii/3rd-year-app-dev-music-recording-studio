<?php
session_start();
require_once 'core/dbConfig.php';
require_once 'core/models.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<a href="core/handleForms.php?logout=true">Logout</a>
	<h1>Welcome To Music Recording Studio System</h1>
    <p>Insert the following table: </p>
	<form action="core/handleForms.php" method="POST">
		<p>
			<label for="album_name">Album Name</label> 
			<input type="text" name="album_name" required>
		</p>
		<p>
			<label for="artist_name">Artist Name</label> 
			<input type="text" name="artist_name" required>
		</p>
		<p>
			<label for="producer_name">Producer Name</label> 
			<input type="text" name="producer_name" required>
		</p>
		<p>
			<label for="status">Status</label> 
			<input type="text" name="status">
		</p>
        <p>
			<label for="release_date">Release</label> 
			<input type="date" name="release_date">
            <input type="submit" name="insertAlbumBtn">
			<input type="reset" value="Reset">
		</p>
	</form>

		<table style="width:100%; margin-top: 50px;">
	  <tr>
	    <th>Album ID</th>
		<th>Album Name</th>
	    <th>Artist Name</th>
	    <th>Producer Name</th>
	    <th>Status</th>
		<th>Release Date</th>
	    <th>Added By</th>
		<th>Modified By</th>
		<th>Date_Added</th>
	    <th>Action</th>
	  </tr>
	  <?php $getAllAlbum = getAllAlbum($pdo); ?>
	  <?php foreach ($getAllAlbum as $row) { ?>
	  <tr>
	  	<td><?php echo $row['album_id']; ?></td>	  	
	  	<td><?php echo $row['album_name']; ?></td>	  	
		<td><?php echo $row['artist_name']; ?></td>	  	
	  	<td><?php echo $row['producer_name']; ?></td>	  	
	  	<td><?php echo $row['status']; ?></td>
		<td><?php echo $row['release_date']; ?></td>	 
		<td><?php echo $row['added_by']; ?></td>	 
		<td><?php echo $row['last_modified_by']; ?></td> 	
	  	<td><?php echo $row['date_added']; ?></td>
	  	<td>
	  		<a href="viewTracks.php?album_id=<?php echo $row['album_id']; ?>">View Tracks</a>
			<a href="editAlbum.php?album_id=<?php echo $row['album_id']; ?>">Edit</a>
			<a href="deleteAlbum.php?album_id=<?php echo $row['album_id']; ?>">Delete</a>
	  	</td>	  	
	  </tr>
	<?php } ?>
	</table>
</body>
</html>