<?php
/* PostTypes Test cases generated on: 2010-05-12 21:05:31 : 1273721131*/
App::import('Controller', 'PostTypes');

class TestPostTypesController extends PostTypesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class PostTypesControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.post_type', 'app.post', 'app.user', 'app.category', 'app.comment', 'app.field');

	function startTest() {
		$this->PostTypes =& new TestPostTypesController();
		$this->PostTypes->constructClasses();
	}

	function endTest() {
		unset($this->PostTypes);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testView() {

	}

	function testAdd() {

	}

	function testEdit() {

	}

	function testDelete() {

	}

}
?>