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
	<h1>Are you sure you want to delete this album?</h1>
	<?php $getAlbumByID = getAlbumByID($pdo, $_GET['album_id']); ?>
	<div class="container">
		<h2>Album Name: <?php echo $getAlbumByID['album_name']; ?></h2>
		<h2>Artist Name: <?php echo $getAlbumByID['artist_name']; ?></h2>
		<h2>Producer Name: <?php echo $getAlbumByID['producer_name']; ?></h2>
		<h2>Status: <?php echo $getAlbumByID['status']; ?></h2>
		<h2>Release Date: <?php echo $getAlbumByID['release_date']; ?></h2>
		<h2>Date Added: <?php echo $getAlbumByID['date_added']; ?></h2>
		<h2>Added by: <?php echo $getAlbumByID['added_by']; ?></h2>
		<h2>Last Modified by: <?php echo $getAlbumByID['last_modified_by']; ?></h2>
	</div>
	<div class="deleteBtn" style="float: right; margin-right: 10px;">
		<form action="core/handleForms.php?album_id=<?php echo $_GET['album_id']; ?>" method="POST">
			<input type="button" value="Go Back" onclick="history.back()">
			<input type="submit" name="deleteAlbumBtn" value="Delete">
		</form>			
	</div>	
</body>
</html>