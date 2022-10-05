<?php /** @var $e Exception */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title><?=$title ?? $e->getCode()?></title>
</head>
<body>
    <h1><?php echo $e->getCode() ?: ''; ?></h1>
	<h3 class="center"><?=$e->getMessage()?></h3>
	<pre><?php print_r(\Simfa\Framework\Application::$APP->db->pdo->errorInfo())?></pre>
    <pre>
        <?=$e->getTraceAsString()?>
	</pre>
</body>
</html>
