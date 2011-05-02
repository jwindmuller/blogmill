<?php
class PluginsController extends AppController {

	var $name = 'Plugins';
    var $uses = null;

    public function dashboard_page($plugin, $page) {
        $path = App::pluginPath($plugin) . 'views' . DS;
        $_app = App::getInstance();
        array_unshift($_app->views, $path);
        
        $this->Blogmill->pluginsAttached('dashboard_page', compact('page'));
        $this->render(null, null, '/dashboard/' . $page);
    }
}
