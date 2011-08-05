<?php
    $type = implode(',', $types);
    $themeLinks = Set::extract( '/themes/.[plugin='. $activeThemePlugin . ']', $this->viewVars );
    $themeLinks = $themeLinks[0]['links'];
    $title = '';
    if ( isset( $themeLinks[$type] )) {
        $title = $themeLinks[$type]['name'];
    } else if ( count($types) == 1) {
        $type = $types[0];
        list($plugin, $type) = pluginSplit($type);
        $title = Inflector::pluralize($postTypes[$plugin][$type]['name']);
    }
    $this->viewVars['title_for_layout'] = $title;
?>
<h1><?php echo $title; ?></h1>
<?php
if ($posts) :
    foreach ($posts as $i => $post):
        list($plugin, $type) = explode('.', $post['Post']['type']);
        $type = strtolower(Inflector::underscore($type));
        $themeView = App::pluginPath($activeThemePlugin) .
                     'views' . DS . 'elements' . DS . $type . DS . 'index-item.ctp';
        $options = compact('plugin', 'post');
        if ( file_exists($themeView) ) {
            $options['plugin'] = $activeThemePlugin;
        }
        echo $this->element($type . "/index-item", $options);
    endforeach;
else:
    echo '<p>', sprintf(__('No %s here yet', true), $typePlural), '</p>';
endif;
?>
