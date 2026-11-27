<?php 
require_once 'core/models.php'; 
require_once 'core/handleForms.php'; 

if (!isset($_SESSION['user_id'])) {
	header("Location: login.php");
	exit;
}
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
	
	<?php if (isset($_SESSION['message'])) { ?>
	<h1 class="wrap-background"><?php echo $_SESSION['message']; ?></h1>
	<?php } unset($_SESSION['message']); ?>
	
	<?php $getAlbumByID = getAlbumByID($pdo, $_GET['album_id']); ?>
	<h1>Album Name: <?php echo $getAlbumByID['album_name']; ?></h1>
	<h1>Add New Track</h1>

	<form action="core/handleForms.php?album_id=<?php echo $_GET['album_id']; ?>" method="POST" enctype="multipart/form-data">
		<p>
			<label for="track_name">Track Name*</label> 
			<input type="text" name="track_name">
		</p>
		<p>
			<label for="image">Track Image</label>
			<input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp">
		</p>
		<p>
			<label for="image_description">Image Description</label>
			<input type="text" id="image_description" name="image_description">
		</p>
		<p>
			<label for="genre">Genre*</label> 
			<input type="text" name="genre">
		</p>
		<p>
			<label for="duration_seconds">Duration In Seconds</label> 
			<input type="number" name="duration_seconds">
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
			<input type="text" name="time_signature">
			<input type="submit" name="insertNewTrackBtn">
			<input type="reset" value="Reset">
		</p>
	</form>

	<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="GET" style="  text-align: right;">
		<input type="hidden" name="album_id" value="<?php echo htmlspecialchars($_GET['album_id']); ?>">
		<input type="text" name="searchInput" placeholder="Search Track">
		<input type="submit" name="searchBtn" value="Search">
	</form>

	<table style="width:100%;">
	  <tr>
		<th>Track Icon</th>
	    <th>Track Name</th>
	    <th>Genre</th>
	    <th>Duration</th>
		<th>BPM</th>
		<th>Key Signature</th>
		<th>Time Signature</th>
	    <th>Added By</th>
		<th>Modified By</th>
		<th>Date Added</th>
		<th>Date Modified</th>
	    <th>Action</th>
	  </tr>

	  <?php if (!isset($_GET['searchBtn'])) { ?>
	  	<?php $getTracksByAlbum = getTracksByAlbum($pdo, $_GET['album_id']); ?>
	  		<?php foreach ($getTracksByAlbum as $row) { ?>
				<tr>
					<td>
						<?php if (!empty($row['images_name'])) { ?>
							<img
								src="uploads/<?php echo htmlspecialchars($row['images_name']); ?>"
								alt="Track image"
								width="80"
							>
						<?php } ?>
					</td>
					<td><?php echo $row['track_name']; ?></td>	  	
					<td><?php echo $row['genre']; ?></td>	  	
					<td class="duration-cell"><?php echo $row['duration_seconds']; ?></td>
					<td><?php echo $row['bpm']; ?></td>	 
					<td><?php echo $row['key_signature']; ?></td>	 
					<td><?php echo $row['time_signature']; ?></td> 	 	 
					<td><?php echo $row['added_by']; ?></td>
					<td><?php echo $row['last_modified_by']; ?></td> 	
					<td><?php echo $row['date_added']; ?></td>
					<td><?php echo $row['date_modified']; ?></td>
					<td>
						<a href="editTracks.php?track_id=<?php echo $row['track_id']; ?>&album_id=<?php echo $_GET['album_id']; ?>">Edit</a>
						<a href="deleteTracks.php?track_id=<?php echo $row['track_id']; ?>&album_id=<?php echo $_GET['album_id']; ?>">Move to Trash</a>
					</td>	  	
				</tr>
			<?php } ?>
		<?php } else { ?> 
		<?php $searchTrack = searchTrack($pdo, $_GET['album_id'], $_GET['searchInput']); ?>
			<?php foreach ($searchTrack as $row) { ?>
				<tr>
					<td><?php echo $row['track_id']; ?></td>	  	
					<td><?php echo $row['track_name']; ?></td>	  	
					<td><?php echo $row['genre']; ?></td>	  	
					<td class="duration-cell"><?php echo $row['duration_seconds']; ?></td>
					<td><?php echo $row['bpm']; ?></td>	 
					<td><?php echo $row['key_signature']; ?></td>	 
					<td><?php echo $row['time_signature']; ?></td>	 	 
					<td><?php echo $row['added_by']; ?></td>
					<td><?php echo $row['last_modified_by']; ?></td> 	
					<td><?php echo $row['date_added']; ?></td>
					<td><?php echo $row['date_modified']; ?></td>
					<td>
						<a href="editTracks.php?track_id=<?php echo $row['track_id']; ?>&album_id=<?php echo $_GET['album_id']; ?>">Edit</a>
						<a href="deleteTracks.php?track_id=<?php echo $row['track_id']; ?>&album_id=<?php echo $_GET['album_id']; ?>">Move to Trash</a>
					</td>	  	
				</tr>
		<?php } ?>
	<?php } ?>
	</table>

	<p>	<a href="viewTrash.php?album_id=<?php echo $_GET['album_id']; ?>">View Trash</a></p>

	<script>
	document.addEventListener("DOMContentLoaded", () => {
		const durationCells = document.querySelectorAll(".duration-cell");
		
		durationCells.forEach(cell => {
			const totalSeconds = parseInt(cell.textContent.trim(), 10);
			
			if (!isNaN(totalSeconds) && totalSeconds >= 0) {
				const minutes = Math.floor(totalSeconds / 60);
				const seconds = totalSeconds % 60;
				const formattedSeconds = seconds < 10 ? "0" + seconds : seconds;
				
				cell.title = `${totalSeconds} seconds`;
				cell.textContent = `${minutes}:${formattedSeconds}`;
			}
		});
	});
	</script>
</body>
</html> 