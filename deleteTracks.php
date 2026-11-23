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
	<?php $getTrackByID = getTrackByID($pdo, $_GET['track_id']); ?>
	<h1>Are you sure you want to move this track to trash?</h1>
	<div class="container" style="border-style: solid; height: 430px;">
		<h2>Track Name: <?php echo $getTrackByID['track_name'] ?></h2>
		<h2>Genre: <?php echo $getTrackByID['genre'] ?></h2>
		<h2>Duration In Seconds: <?php echo $getTrackByID['duration_seconds'] ?></h2>
        <h2>BPM: <?php echo $getTrackByID['bpm'] ?></h2>
        <h2>Key Signature: <?php echo $getTrackByID['key_signature'] ?></h2>
        <h2>Time Signature: <?php echo $getTrackByID['time_signature'] ?></h2>
		<h2>Added By: <?php echo $getTrackByID['added_by'] ?></h2>
		<h2>Last Modified By: <?php echo $getTrackByID['last_modified_by'] ?></h2>
		<h2>Date Added: <?php echo $getTrackByID['date_added'] ?></h2>

		<div class="deleteBtn" style="float: right; margin-right: 10px;">

			<form action="core/handleForms.php?track_id=<?php echo $_GET['track_id']; ?>&album_id=<?php echo $_GET['album_id']; ?>" method="POST">
				<input type="button" value="Go Back" onclick="history.back()">
				<input type="submit" name="deleteTrackBtn" value="Move to Trash">
			</form>			
			
		</div>	

	</div>
</body>
</html>