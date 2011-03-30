<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC
    "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Sandbox Admin</title>
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