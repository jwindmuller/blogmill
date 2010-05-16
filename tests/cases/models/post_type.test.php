<?php
/* PostType Test cases generated on: 2010-05-12 21:05:30 : 1273721130*/
App::import('Model', 'PostType');

class PostTypeTestCase extends CakeTestCase {
	var $fixtures = array('app.post_type', 'app.post', 'app.user', 'app.category', 'app.comment', 'app.field');

	function startTest() {
		$this->PostType =& ClassRegistry::init('PostType');
	}

	function endTest() {
		unset($this->PostType);
		ClassRegistry::flush();
	}

}
?>