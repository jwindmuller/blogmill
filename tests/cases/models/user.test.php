<?php
/* User Test cases generated on: 2010-05-12 21:05:35 : 1273721135*/
App::import('Model', 'User');

class UserTestCase extends CakeTestCase {
	var $fixtures = array('app.user', 'app.post', 'app.post_type', 'app.category', 'app.comment', 'app.field');

	function startTest() {
		$this->User =& ClassRegistry::init('User');
	}

	function endTest() {
		unset($this->User);
		ClassRegistry::flush();
	}

}
?>