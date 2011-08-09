<?php foreach( $posts as $i => $post ) : ?>
<?php
    list($plugin, $type) = pluginSplit($this->Blogmill->field($post, 'type'));
    $type = strtolower($type);
    $return = true;
    echo $this->element($type . DS . 'rss/index-item', compact('plugin', 'post'));
    //var_dump($post);
?>
<?php endforeach; ?>
