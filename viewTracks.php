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
	<a href="index.php">Return to home</a>
	<?php $getAlbumByID = getAlbumByID($pdo, $_GET['album_id']); ?>
	<h1>Album Name: <?php echo $getAlbumByID['album_name']; ?></h1>
	<h1>Add New Track</h1>
	<form action="core/handleForms.php?album_id=<?php echo $_GET['album_id']; ?>" method="POST">
		<p>
			<label for="track_name">Track Name</label> 
			<input type="text" name="track_name" required>
		</p>
		<p>
			<label for="genre">Genre</label> 
			<input type="text" name="genre" required>
		</p>
		<p>
			<label for="duration_seconds">Duration In Seconds</label> 
			<input type="number" name="duration_seconds" required>
		</p>
		<p>
			<label for="bpm">BPM</label> 
			<input type="number" name="bpm">
		</p>
		<p>
			<label for="key_signature">Key Signature</label> 
			<input type="text" name="key_signature">
		</p>
		<p>
			<label for="time_signature">Time Signature</label> 
			<input type="text" name="time_Signature">
			<input type="submit" name="insertNewTrackBtn">
			<input type="reset" value="Reset">
		</p>
	</form>

	<table style="width:100%; margin-top: 50px;">
	  <tr>
	    <th>Track ID</th>
	    <th>Track Name</th>
	    <th>Genre</th>
	    <th>Duration in Seconds</th>
		<th>BPM</th>
		<th>Key Signature</th>
		<th>Time Signature</th>
		<th>Artist Name</th>
		<th>Producer Name</th>
	    <th>Added By</th>
		<th>Modified By</th>
		<th>Date Added</th>
	    <th>Action</th>
	  </tr>
	  <?php $getTrackByAlbum = getTracksByAlbum($pdo, $_GET['album_id']); ?>
	  <?php foreach ($getTrackByAlbum as $row) { ?>
	  <tr>
	  	<td><?php echo $row['track_id']; ?></td>	  	
	  	<td><?php echo $row['track_name']; ?></td>	  	
	  	<td><?php echo $row['genre']; ?></td>	  	
	  	<td><?php echo $row['duration_seconds']; ?></td>
		<td><?php echo $row['bpm']; ?></td>	 
		<td><?php echo $row['key_signature']; ?></td>	 
		<td><?php echo $row['time_signature']; ?></td>
		<td><?php echo $row['artist_name']; ?></td>	 
		<td><?php echo $row['producer_name']; ?></td>	 	 	 
		<td><?php echo $row['added_by']; ?></td>
		<td><?php echo $row['last_modified_by']; ?></td> 	
	  	<td><?php echo $row['date_added']; ?></td>
	  	<td>
	  		<a href="editTracks.php?track_id=<?php echo $row['track_id']; ?>&album_id=<?php echo $_GET['album_id']; ?>">Edit</a>
	  		<a href="deleteTracks.php?track_id=<?php echo $row['track_id']; ?>&album_id=<?php echo $_GET['album_id']; ?>">Delete</a>
	  	</td>	  	
	  </tr>
	<?php } ?>
	</table>

	
</body>
</html> 