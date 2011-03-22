<?php
    $fixed_pages = array(
        array('Post' => array(
            'display' => __('Home', true),
            'excerpt' => __('Link to the homepage', true),
            'url' => Router::url('/', false)
        )),
        array('Post' => array(
            'display' => __('Contact', true),
            'excerpt' => __('Link to the contact page', true),
            'url' => Router::url(array('controller' => 'contacts', 'action' => 'send', 'dashboard' => false), false)
        ))
    );
    echo $this->Javascript->object($fixed_pages);
?>
