
<?php
ipAddCss('../assets/Track.css');
?>

<section>
	<h3>Hello plugin</h3>
	<p><?= isset($name) && !empty($name) ? $name : '[No name]' ?></p>
</section>
