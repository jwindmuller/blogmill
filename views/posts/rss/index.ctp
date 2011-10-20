<?php foreach( $posts as $i => $post ) : ?>
<?php
    list($plugin, $type) = pluginSplit($this->Blogmill->field($post, 'type'));
    $type = strtolower($type);
    $return = true;
    
    $themeView = App::pluginPath($activeThemePlugin) . 'views' . DS . 'elements' . DS . $type . DS . 'rss' . DS . 'index-item.ctp';
    var_dump($themeView);
    $options = compact('plugin', 'post');
    if ( file_exists($themeView) ) {
        $options['plugin'] = $activeThemePlugin;
    }
    echo $this->element($type . DS . 'rss/index-item', $options);
?>
<?php endforeach; ?>