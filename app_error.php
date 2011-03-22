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
        $schema = new CakeSchema(array('name' => 'App'));
        $schema = $schema->load();
        $db =& ConnectionManager::getDataSource($schema->connection);
        foreach ($schema->tables as $table => $fields) {
            $db->execute($db->createSchema($schema, $table));
        }
    }
 
}

