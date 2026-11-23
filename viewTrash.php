<?php
require_once 'core/models.php';
require_once 'core/handleForms.php';

if (!isset($_SESSION['user_id'])) {
	header("Location: login.php");
	exit;
}

$deletedTracks = getAllDeletedTracks($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Track Trash</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<a href="index.php">Return to home</a>
	<h1>Trashed Tracks</h1>

	<?php if (isset($_SESSION['message'])) { ?>
		<p><?php echo htmlspecialchars($_SESSION['message']); ?></p>
	<?php unset($_SESSION['message']); } ?>

	<table style="width:100%;">
		<tr style="text-align: left;">
			<th>Track Name</th>
			<th>Genre</th>
			<th>Album</th>
			<th>Deleted By</th>
			<th>Deleted At</th>
			<th>Action</th>
		</tr>
		<?php foreach ($deletedTracks as $row) { ?>
			<tr>
				<td><?php echo htmlspecialchars($row['track_name']); ?></td>
				<td><?php echo htmlspecialchars($row['genre']); ?></td>
				<td><?php echo htmlspecialchars($row['album_name']); ?></td>
				<td><?php echo htmlspecialchars($row['deleted_by'] ?? 'Unknown'); ?></td>
				<td><?php echo htmlspecialchars($row['deleted_at']); ?></td>
				<td>
					<form action="core/handleForms.php?track_id=<?php echo $row['track_id']; ?>" method="POST" style="display:inline;">
						<input type="submit" class="style-button" name="restoreTrackBtn" value="Restore">
						<input type="submit" class="style-button" name="deleteTrackPermanentlyBtn" value="Delete Permanently" onclick="return confirm('Permanently delete this track? This cannot be undone.');">
					</form>
				</td>
			</tr>
		<?php } ?>
	</table>
</body>
</html>