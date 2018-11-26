<!DOCTYPE html>
<html>
<head>
	<title>404 Not Found</title>
</head>
<style type="text/css">
	img {
		width: 100%;
		height: 100%;
	}
</style>
<body>
	<?php 
		function displayError($path) {
			echo "<img src=\"${path}/error_404.png\"/>";
		}
	?>
</body>
</html>