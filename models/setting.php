<?php
App::import(array('type' => 'File', 'name' => 'BlogmillSettings', 'file' => APP . 'config/blogmill_settings.php'));
class Setting extends AppModel {
	var $name = 'Setting';
	var $displayField = 'name';
	var $validate = array(
		'name' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'required' => true
			)
		),
		'value' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'required' => true
			)
		)
	);
	
	/**
	 * Initialize Validation
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	protected function __initializeValidation() {
		$this->defineErrorMessage('value.required', __('This Setting is required', true));
	}
	
	public function id($name) {
		return $this->field('id', compact('name'));
	}
	
	public function store($name, $value) {
		$id = $this->id($name);
		$fieldConfig = $this->__getFieldConfig($name);
		if ( $fieldConfig && isset($fieldConfig['multi']) ) {
			$value = serialize($value);
		}
		return $this->save(compact('id', 'name', 'value'));
	}
	
	public function get($name) {
		$value = $this->field('value', compact('name'));
		$fieldConfig = $this->__getFieldConfig($name);
		if ( $fieldConfig && isset($fieldConfig['multi']) ) {
			$unserialized = @unserialize($value);
			if ( $unserialized !== false ) {
				$value = $unserialized;
			}
		}
		return $value;
	}

	private function __getFieldConfig($name) {
		list($plugin, $settingName) = pluginSplit($name);
		if ($plugin) {
			$class = "${plugin}Settings";
			$pluginPath = App::pluginPath($plugin);
			App::import(
				array(
					'type' => 'File',
					'name' => $class,
					'file' => $pluginPath . DS . 'config' . DS . 'settings.php',
					''
				)
			);
			$pluginSettings = new $class();
			$configurable = @$pluginSettings->configurable[$settingName];
			return $configurable;
		}
		return false;
	}
}
?>