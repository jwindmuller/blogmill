<?php
    $typePlural = Inflector::pluralize($postTypes[$plugin][$type]['name']);
    $this->viewVars['title_for_layout'] = $typePlural;
?>
<h1><?php echo $typePlural; ?></h1>
<?php
if ($posts) :
    foreach ($posts as $i => $post):
        list($plugin, $type) = explode('.', $post['Post']['type']);
        echo $this->element(
            strtolower(Inflector::underscore($type)) . "/index",
            array('plugin' => $plugin, 'post' => $post)
        );
    endforeach;
else:
    echo '<p>', sprintf(__('No %s here yet', true), $typePlural), '</p>';
endif;
?>
