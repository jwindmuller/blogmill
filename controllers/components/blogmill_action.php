<?php
class BlogmillActionComponent extends Object {
    
    protected $Controller;

    public function startup($Controller) {
        $this->Controller = $Controller;
    }

    final function execute_plugin_action($plugin, $action) {
        $pluginName = Inflector::camelize($plugin);
        $path = App::pluginPath($plugin) . 'controllers' . DS . 'components' . DS . $plugin .'_actions.php';
        $className = $pluginName . 'ActionsComponent';
        App::import(
            array(
                'type' => 'Component',
                'name' => $pluginName . '.' . $className,
                'file' => $path
            )
        );
        if (class_exists($className) && method_exists($className, $action)) {
            $extra_params = $this->Controller->params['pass'];
            unset($extra_params[0], $extra_params[1]);
            $extra_params = array_values($extra_params);
            $obj = new $className;
            $obj->{$action}($this->Controller, $extra_params);
        }
    }

}