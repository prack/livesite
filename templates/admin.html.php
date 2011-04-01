<html>
<head>
	<title>Livesite Admin Area</title>
	<link rel="stylesheet" href="/livesite/static/styles.css" />
	<link rel="icon" type="image/png" href="/livesite/static/adminfav.png" />
</head>
<body>
	<?php include "_navigation.html.php"; ?>
	<div id="body">
		<h1>Admin site (logged in as '<?php echo Prack_Utils::singleton()->escapeHTML( $user ); ?>')</h1>
	</div>
</body>
</html>