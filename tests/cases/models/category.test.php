<?php
/* Category Test cases generated on: 2010-05-12 21:05:03 : 1273721103*/
App::import('Model', 'Category');

class CategoryTestCase extends CakeTestCase {
	var $fixtures = array('app.category', 'app.post', 'app.post_type', 'app.user', 'app.comment', 'app.field');

	function startTest() {
		$this->Category =& ClassRegistry::init('Category');
	}

	function endTest() {
		unset($this->Category);
		ClassRegistry::flush();
	}

}
?>