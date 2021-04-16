<title><?=$title ?? ''?></title>
<?php /** @var $e Exception */ ?>
<h1><?php echo $e->getCode(); ?></h1>
<p>
	<?=$e->getMessage()?>
</p>