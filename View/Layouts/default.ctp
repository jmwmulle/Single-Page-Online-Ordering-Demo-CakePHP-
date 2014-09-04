<!doctype html>
<html class="no-js" lang="en">
<head>
	<?php echo $this->Html->charset(); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>XtremePizza - <?php echo $title_for_layout;?></title>
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
<?php $this->start('main');?>
	<main id="menu" class="pane" >
		<div class="row" >
			<div class="large-2 columns xtreme-blue" ></div >
			<div class="large-10 columns" ></div >
		</div >
		<div class="row" >
			<nav id="subnav" class="large-2 columns xtreme-blue">
			<?php if ($this->get('subnavContents')) echo $this->Elements( 'subnav', array( 'elements' => $subnavContents ) );?>
			</nav>
			<div class="large-10 columns">
				<div class="row">
					<nav id="topnav" class="large-12 columns">
						<?php if ($this->get('topnav')) echo $this->Element('topnav', array('navopts' => $topnav, 'selected' => $here));?>
					</nav>
					<section id="menu-content">
						<div id="content-area" class="large-9 columns">
							<?php if (!$this->get('isSplash') ) echo $this->fetch('content');?>
						</div>
							<nav id="subnav-toc" class="large-3 columns">
								<ul>
								</ul>
							</nav>
					</section>
				</div>
			</div>
		</div>
	</main>

<?php
	$this->end('main');

	if ($this->get('isSplash')) echo $this->fetch('content');
	echo $this->fetch('main');
	echo $this->fetch('app');
?>
</body>
</html>
