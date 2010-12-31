<?php
class Setting extends AppModel {
	var $name = 'Setting';
	var $displayField = 'name';
	
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