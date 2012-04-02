<?php
    foreach($posts as $i => $post) {
        $posts[$i]['Post']['url'] = Router::url($this->Blogmill->postURL($post), false);
    }
    $humanized = Inflector::humanize(Inflector::underscore(Inflector::pluralize($type)));
    if ($plugin !== "") {
        $humanized = __d(Inflector::underscore($plugin), $humanized, true);
    } else {
        $humanized = __($humanized, true);
    }
    array_unshift($posts, array('Post' => array(
        'display' => sprintf(__('List of %s', true), $humanized),
        'excerpt' => '',
        'url' => $this->Html->url(array('controller' => 'posts', 'action' => 'index', 'type' => $plugin . '.' . $type, 'dashboard' => false))
    )));

    echo $this->Javascript->object($posts);
