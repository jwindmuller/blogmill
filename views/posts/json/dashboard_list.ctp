<?php
    foreach($posts as $i => $post) {
        $posts[$i]['Post']['url'] = Router::url($this->Blogmill->postURL($post), false);
    }
    $types = join(',', $types);
    array_unshift($posts, array('Post' => array(
        'display' => sprintf(__('List of %s', true), BlogmillRouteFunctions::getIndexName($types)),
        'excerpt' => '',
        'url' => $this->Html->url(array('controller' => 'posts', 'action' => 'index', $types, 'dashboard' => false))
    )));

    echo $this->Javascript->object($posts);
