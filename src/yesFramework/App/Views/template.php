<!DOCTYPE html>
<html lang="pl">

<head>
	<meta charset="utf-8" />
	<meta name="Author" content="Marcin Romanowicz" />
	<link rel="stylesheet" href="css/style.css" type="text/css" />
	<title><?php echo NAME; ?></title>
</head>

<body>
	<header>
		<div id="header"><img id="logo" src="img/logo-yesframework.png" /><br /><?php echo $header; ?></div>
	</header>
	<main>
		<?php echo $body_content;?>
	</main>
	<footer>
		<div id="footer"><?php echo $footer; ?></div>
	</footer>
</body>

</html>