<?php
    foreach($posts as $i => $post) {
        $posts[$i]['Post']['url'] = Router::url($this->Blogmill->postURL($post), true);
    }

    echo $this->Javascript->object($posts);
