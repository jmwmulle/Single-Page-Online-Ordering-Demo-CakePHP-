<h2>Logged In!</h2>
<ul>
<?php foreach ($this->data as $item) {
	echo '<li>';
	echo pr($item), '</li>';
} ?>
</ul>
