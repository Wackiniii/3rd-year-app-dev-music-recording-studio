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
	<a href="viewTracks.php?album_id=<?php echo $_GET['album_id']; ?>">
	View Tracks:</a>
	<h1>Edit the track</h1>
	<?php $getTrackByID = getTrackByID($pdo, $_GET['track_id']); ?>
	<form action="core/handleForms.php?track_id=<?php echo $_GET['track_id']; ?>
	&album_id=<?php echo $_GET['album_id']; ?>" method="POST">
		<p>
			<label for="track_name">Track Name*</label> 
			<input type="text" name="track_name" 
			value="<?php echo $getTrackByID['track_name']; ?>">
		</p>
		<p>
			<label for="genre">Genre*</label> 
			<input type="text" name="genre" 
			value="<?php echo $getTrackByID['genre']; ?>">
		</p>
		<p>
			<label for="duration_seconds">Duration in Seconds</label> 
			<input type="number" name="duration_seconds" 
			value="<?php echo $getTrackByID['duration_seconds']; ?>">
		</p>
		<p>
			<label for="bpm">BPM</label> 
			<input type="number" name="bpm" 
			value="<?php echo $getTrackByID['bpm']; ?>">
		</p>
        <p>
			<label for="key_signature">Key Signature</label> 
			<input type="text" name="key_signature" 
			value="<?php echo $getTrackByID['key_signature']; ?>">
		</p>
        <p>
			<label for="time_signature">Time Signature</label> 
			<input type="text" name="time_signature" 
			value="<?php echo $getTrackByID['time_signature']; ?>">

		</p>
		<p>
			<input type="button" value="Go Back" onclick="history.back()">
            <input type="submit" name="editTrackBtn">
			<input type="reset" value="Reset">
		</p>
	</form>

	<?php if (isset($_SESSION['message'])) { ?>
		<h1 style="color: green;"><?php echo $_SESSION['message']; ?></h1>
	<?php } unset($_SESSION['message']); ?>
</body>
</html>