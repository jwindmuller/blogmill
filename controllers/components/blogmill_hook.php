<?php
class BlogmillHookComponent extends Object {
	
	protected $Controller;
	
	public function startup($Controller) {
		$this->Controller = $Controller;
	}
}