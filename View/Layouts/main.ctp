<!doctype html>
<html class="no-js" lang="en">
<head>
	<?php echo $this->Html->charset(); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>Xtreme Pizza - Welcome!</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css(array("http://fonts.googleapis.com/css?family=Fredericka+the+Great", "http://fonts.googleapis.com/css?family=Mouse+Memoirs"));
		echo $this->Html->css(array("/bower_components/foundation/css/normalize",'app'));
		echo $this->Html->script("/bower_components/modernizr/modernizr");
		echo $this->Html->script(array("lib/selectorManifest.js",
			"/bower_components/jquery/dist/jquery.min",
			"//code.jquery.com/ui/1.11.1/jquery-ui.js",
			"utilities",
			"/bower_components/foundation/js/foundation.min",
			"lib/bootstrap"), array('block' => 'app'));
	?>
</head>
<body>
<?php
	$vv = $this->getVars();
	db($vv);
	ViewBlock::start('main');?>

<?php
	echo $this->fetch('app');
?>
</body>
</html>
