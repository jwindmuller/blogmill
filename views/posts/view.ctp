<?php 
	list($plugin, $type) = explode('.', $post['Post']['type']);
	$plugin = Inflector::underscore($plugin);
	$type = Inflector::underscore($type);
?> 
<div class="posts view">
	<?php echo $this->element($type . '/view', compact('plugin')); ?> 
</div>
<?php 
	$type = Set::extract($postTypes, $post['Post']['type']);
	if (!isset($type['comments']) || $type['comments']) {
		echo $this->element('comments.form');
	}
?>