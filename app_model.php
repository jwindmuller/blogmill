<?php
class AppModel extends Model {
	
	/**
	 * Define a Error Message for a rule.
	 * To be used on each model's __construct method
	 *
	 * @param string $path Path to the rule defined in array dot notation. "field.rule"
	 * @param string $message message to assign
	 * @return void
	 * @author Joaquin Windmuller
	 */
	protected function defineErrorMessage($path, $message) {
		$this->validate = Set::insert($this->validate, $path . '.message', $message);
	}
	
	/**
	 * Initialize Validation
	 * Stub. Must be defined by models to create internationalized validation messages
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	protected function __initializeValidation() {}
	
	/**
	 * Adds a call to __initializeValidation and calls Model::__construct
	 *
	 * @param string $id 
	 * @param string $table 
	 * @param string $ds 
	 * @see Model::__construct
	 * @author Joaquin Windmuller
	 */
	function __construct($id = false, $table = null, $ds = null) {
		$this->__initializeValidation();
		parent::__construct($id, $table, $ds);
	}

	/**
	 * Validation rule to check if a field is unique
	 *
	 * @param array $data 
	 * @return void
	 */
	function validateUnique($data) {
		$name = array_pop(array_keys($data));
		if (!empty($this->id)) {
			$conditions = array('NOT' => array($this->primaryKey => $this->id), 'AND' => array($name => $data[$name]));
		} else {
			$conditions = array($name => $data[$name]);
		}
		$result = $this->field($this->primaryKey, $conditions);
		return $result === false;
	}
	
}
?>
