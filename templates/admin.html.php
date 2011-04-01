<html>
<head>
	<title>Livesite Admin Area</title>
	<link rel="stylesheet" href="/static/styles.css" />
	<link rel="icon" type="image/png" href="/static/adminfav.png" />
</head>
<body>
	<?php include "_navigation.html.php"; ?>
	<div id="body">
		<h1>Admin site (logged in as '<?php echo Prack_Utils::singleton()->escapeHTML( $user ); ?>')</h1>
	</div>
</body>
</html>