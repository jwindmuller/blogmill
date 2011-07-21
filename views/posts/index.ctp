<?php $typePlural = Inflector::pluralize($postTypes[$plugin][$type]['name']); ?>
<h1><?php echo $typePlural; ?></h1>
<?php if ($posts) : ?>
<?php foreach ($posts as $i => $post): list($plugin, $type) = explode('.', $post['Post']['type']); ?>
	<?php echo $this->element(Inflector::underscore($type) . "/index", array('plugin' => $plugin, 'post' => $post)); ?>
<?php endforeach ?>
<?php else: ?>
    <?php printf(__('No %s here yet', true), $typePlural); ?>
<?php endif;?>