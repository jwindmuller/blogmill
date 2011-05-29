<?php
    $this->title = $this->Blogmill->field($post, 'display');

	list($plugin, $type) = explode('.', $post['Post']['type']);
	$plugin = Inflector::underscore($plugin);
	$type = Inflector::underscore($type);
    $theme_view = APP . 'plugins' . DS . strtolower($activeThemePlugin) . DS . 'views' . DS . 'elements' . DS . $type . DS . 'view.ctp';
    if (is_file($theme_view)) $plugin =  $activeThemePlugin;
?> 
<div class="posts view">
	<?php echo $this->element($type . '/view', compact('plugin', 'post')); ?> 
</div>
<?php 
	$type = Set::extract($postTypes, $post['Post']['type']);
	if (!isset($type['comments']) || $type['comments']) {
		echo $this->element('comments.form');
	}
?>
