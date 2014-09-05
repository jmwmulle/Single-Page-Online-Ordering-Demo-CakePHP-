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
			"vendor/jquery.mousewheel.min",
			"utilities",
			"/bower_components/foundation/js/foundation.min",
			"lib/bootstrap",
			"application"), array('block' => 'app'));

	?>
<script type="text/javascript">var isSplash =<?php echo ($this->get("isSplash")) ? 'true' : 'false';?>;</script>
</head>

<body id="body-content">
<?php $this->start('main');?>
	<main id="menu" class="pane loading" >
		<div class="row" >
			<div class="large-2 columns xtreme-blue top-spacer" ></div >
			<div class="large-10 columns" ></div >
		</div >
		<div class="row" >
			<nav id="subnav" class="large-2 columns xtreme-blue">
			<?php if ($this->get('subnav')) echo $this->Element( 'subnav', array( 'elements' => $subnav ) );?>
			</nav>
			<div class="large-10 columns">
				<div class="row">
					<nav id="topnav" class="large-12 columns">
						<?php
							if ($this->get('topnav') && $this->get('here')) {
							echo $this->Element('topnav', array('navopts' => $topnav, 'selected' => $here));}?>
					</nav>
					<section class="content">
						<div class="large-9 columns">
							<div>
								<?php $scrollUpAttr = array('scroll' => 'parent', 'scroll-direction' => 'up');
									  $scrollDownAttr = array('scroll' => 'parent', 'scroll-direction' => 'down');?>
								<nav class="scroll-area up" <?php echo ___dA($scrollUpAttr);?>></nav>
									<div id="primary-content" class="large-12 columns content-area">
										<?php if (!$this->get('isSplash') ) echo $this->fetch('content');?>
									</div>
								<nav class="scroll-area down" <?php echo ___dA($scrollDownAttr);?>></nav>
							</div>
						</div>
						<nav id="subnav-toc" class="large-3 columns">
							<ul class="container">
							<?php if ($this->get('toc') ) {
										foreach($toc as $snt) {
											$data = array("scroll-to" => ___strToSel($snt), "scroll-target" => "#primary-content");
											?>
								<li <?php echo ___dA($data);?>><?php echo ucwords($snt);?></li>
							<?php }}?>
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
