<?php
/* Field Test cases generated on: 2010-05-12 21:05:15 : 1273721115*/
App::import('Model', 'Field');

class FieldTestCase extends CakeTestCase {
	var $fixtures = array('app.field', 'app.post', 'app.post_type', 'app.user', 'app.category', 'app.comment');

	function startTest() {
		$this->Field =& ClassRegistry::init('Field');
	}

	function endTest() {
		unset($this->Field);
		ClassRegistry::flush();
	}

}
?>