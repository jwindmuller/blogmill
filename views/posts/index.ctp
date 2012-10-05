<div class="posts index">
<?php
    $type = implode(',', $types);
    $title = '';
    if ( BlogmillRouteFunctions::getIndexName($type) !== false ) {
        $title = BlogmillRouteFunctions::getIndexName($type);
    } else if ( count($types) == 1) {
        $type = $types[0];
        list($plugin, $type) = pluginSplit($type);
        $title = Inflector::pluralize($postTypes[$plugin][$type]['name']);
    }
    $this->viewVars['title_for_layout'] = $title;
?>
<h1 class="title"><?php echo $title; ?></h1>
<?php $subtitle = BlogmillRouteFunctions::specialField($type, 'subtitle'); ?>
<?php if ($subtitle): ?>
    <p><?php echo $subtitle; ?></p>
<?php endif ?>
<?php
if ($posts) :
    foreach ($posts as $i => $post):
        list($plugin, $type) = explode('.', $post['Post']['type']);
        $type = strtolower(Inflector::underscore($type));
        $themeView = App::pluginPath($activeThemePlugin) .
                     'views' . DS . 'elements' . DS . $type . DS . 'index-item.ctp';
        $plugin = strtolower($plugin);
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
</div>