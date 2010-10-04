<?php foreach ($posts as $i => $post): list($plugin, $type) = explode('.', $post['Post']['type']); ?>
	<?php echo $this->element(Inflector::underscore($type) . "/index", array('plugin' => $plugin, 'post' => $post)); ?>
<?php endforeach ?>
