<html>
<head>
	<title>Livesite Admin Area</title>
	<link rel="stylesheet" href="/livesite/static/styles.css" />
	<link rel="icon" type="image/png" href="/livesite/static/adminfav.png" />
</head>
<body>
	<div id="floaters">
		<?php include "_navigation.html.php"; ?>
	</div>
	<div id="content" class="admin">
		<?php include "_navigation.html.php"; ?>
		<h1>Admin site (logged in as '<?php echo Prack_Utils::singleton()->escapeHTML( $user ); ?>')</h1>
		<h2>Nothing to see here really, but you find something interesting below. :)</h2>
		<h4><em>To log out, you'll need to clear your browsing history--your browser is storing your credentials!</em></h4>
	</div>
	<div id="diagnostics">
	</div>
</body>
</html>