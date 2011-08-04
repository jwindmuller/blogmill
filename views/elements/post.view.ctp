<?php
    $this->title = $this->Blogmill->field($post, 'display');

	list($plugin, $type) = explode('.', $post['Post']['type']);
	$plugin = Inflector::underscore($plugin);
	$type = Inflector::underscore($type);
    $theme_view = App::pluginPath($activeThemePlugin) . 
                  'views' . DS . 'elements' . DS . $type . DS . 'view-item.ctp';
    if (is_file($theme_view)) {
        $plugin = $activeThemePlugin;
    }
?> 
<div class="post view">
	<?php echo $this->element($type . '/view-item', compact('plugin', 'post')); ?> 
    <?php 
	    $type = Set::extract($postTypes, $post['Post']['type']);
    	if (!isset($type['comments']) || $type['comments']) {
	    	echo $this->element('comments.form');
    	}
    ?>
</div>
