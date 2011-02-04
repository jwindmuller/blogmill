<?php
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
		return $this->save(compact('id', 'name', 'value'));
	}
	
	public function get($name) {
		return $this->field('value', compact('name'));
	}
}
?>