<style type="text/css">
	#runtimes {
		position: absolute;
		top: 2em;
		right: 1em;
		font-style: italic;
		background-color: #ffdddd;
		border-radius: 1em/5em;
		padding: 1em;
		list-style: none;
	}
</style>

<ol id="runtimes">
<?php foreach( $timers as $name => $time ): ?>
	<li id="<?php echo $name; ?>">'<?php echo $name; ?>' time: "<?php echo $time; ?>" seconds</li>
<?php endforeach; ?>
</ol>
