<?php
    $typePlural = Inflector::pluralize($postTypes[$plugin][$type]['name']);
    $this->viewVars['title_for_layout'] = $typePlural;
?>
<h1><?php echo $typePlural; ?></h1>
<?php
if ($posts) :
    foreach ($posts as $i => $post):
        list($plugin, $type) = explode('.', $post['Post']['type']);
        $type = strtolower(Inflector::underscore($type));
        $themeView = APP . 'plugins' . DS . strtolower($activeThemePlugin) . DS . 'views' . DS . 'elements' . DS . $type . DS . 'index.ctp';
        $options = compact('plugin', 'post');
        if ( file_exists($themeView) ) {
            $options['plugin'] = $activeThemePlugin;
        }
        echo $this->element($type . "/index", $options);
    endforeach;
else:
    echo '<p>', sprintf(__('No %s here yet', true), $typePlural), '</p>';
endif;
?>
