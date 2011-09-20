<?php
//App::import('Model', 'CakeSchema', false);
App::import('Lib', 'Migrations.MigrationVersion');

class AppError extends ErrorHandler {
    
    function __construct($method, $messages, $controller = false) {
        $this->controller = &$controller;
        if ( $controller === false ) {
            parent::__construct($method, $messages);
        }
    }

    function _outputMessage($template) {
        if ($template == 'missingTable') {
            $this->upgradeRequired();
            $this->controller->redirect(array(
                'controller' => 'setup',
                'action' => 'go',
                'dashboard' => true
            ));
        }
        // Startup The Blogmill Component to get the current theme info.
        $this->controller->Blogmill->startup($this->controller);
        $this->plugin = $this->controller->activeThemePlugin;
        $this->controller->render($template, $this->controller->layout);
		$this->controller->afterFilter();
		echo $this->controller->output;
    }

    public function error404($params) {
        $this->controller->name = 'CakeError';
        $this->controller->action = 'error404';
		$this->controller->header("HTTP/1.0 404 Not Found");
		echo $this->controller->render('/errors/error404');
        $this->_stop();
    }

    public function upgradeRequired() {
		$this->controller->Blogmill->checkUpgradeRequired();
    }

    private function __installPluginTables() {
        // Load plugin schemas
		$plugins = Configure::listObjects('plugin');
        foreach( $plugins as $plugin ) {
            $file = APP . 'plugins' . DS . Inflector::underscore($plugin) . DS . 'config' . DS . 'schema' . DS . 'schema.php';
            $path = App::pluginPath($plugin) . 'config' . DS . 'schema';
            $schema = new CakeSchema(array(
                'name' => Inflector::underscore($plugin),
                'plugin' => $plugin,
                'path' => $path
            ));
            $schema = $schema->load();
            if ($schema) {
                foreach( $schema->tables as $table => $fields ) {
                    $db->execute($db->createSchema($schema, $table));
                }
            }
        }
    }
 
}

