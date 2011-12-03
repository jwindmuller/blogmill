<?php
class BlogmillHookComponent extends Object {
	
	protected $Controller;
	
	public function startup($Controller) {
		$this->Controller = $Controller;
	}

    public function call($hook_name, $params = array()) {
        $hookName = Inflector::camelize($hook_name);
        $plugins = Configure::listObjects('plugin');
		foreach ($plugins as $i => $plugin) {
            $plugin_folder = Inflector::underscore($plugin);
            $hooks_path =
                APP . 'plugins' . DS . $plugin_folder . DS .
                'controllers' . DS . 'components' . DS;
            $hookClass = "{$plugin}HooksComponent";
            App::import(
				array(
					'type' => 'File',
					'name' => $hookClass,
					'file' => $hooks_path . $plugin_folder . '_hooks.php'
				)
			);
            if (class_exists($hookClass)) {
                $obj = new $hookClass;
                if (method_exists($obj, $hook_name)) {
                    call_user_func(array($obj, $hook_name), &$params);
                }
            }
        }
        return $params;
    }
}