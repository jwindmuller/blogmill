<?php
class AppError extends ErrorHandler {
 
    function __construct($method, $messages) {
        Configure::write('debug', 1);
        parent::__construct($method, $messages);
    }
 
    function _outputMessage($template) {
        if ($template == 'missingTable') {
            $this->__createTablesFromSchema();
           $this->controller->redirect(array('controller' => 'setup', 'action' => 'go', 'dashboard' => true));
        }
        parent::_outputMessage($template);
    }

    private function __createTablesFromSchema() {
        App::import('Model', 'CakeSchema');
        // Main App Schema
        $schema = new CakeSchema(array('name' => 'App'));
        $schema = $schema->load();
        $db =& ConnectionManager::getDataSource($schema->connection);
        foreach ($schema->tables as $table => $fields) {
            $db->execute($db->createSchema($schema, $table));
        }
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

