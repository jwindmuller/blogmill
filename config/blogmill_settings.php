<?php
class BlogmillSettings {	
	
	/**
	 * Name of the plugin
	 *
	 * @var string
	 */
	private $__name = null;
	public $activated = false;
	
	public function __construct() {
		$this->__name = str_replace('Settings', '', get_class($this));
	}
	
	public function fieldLabel() {
		
	}
	
	public function name() {
		return $this->__name;
	}
	
	public function directoryName() {
		return Inflector::underscore($this->__name);
	}
	
	public function path() {
		return APP . 'plugins' . DS . Inflector::underscore($this->__name);
	}
}