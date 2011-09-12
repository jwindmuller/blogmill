<?php
    $custom404 = App::pluginPath(strtolower($activeThemePlugin)) . 'views' . DS . 'elements' . DS . 'errors' . DS . '404.ctp';
    $params = array();
    if (file_exists($custom404)) {
        $params = array('plugin' => strtolower($activeThemePlugin));
    }
    echo $this->element('/errors/404', $params); 
