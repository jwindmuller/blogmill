<?php
/* Fields Test cases generated on: 2010-05-12 21:05:15 : 1273721115*/
App::import('Controller', 'Fields');

class TestFieldsController extends FieldsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class FieldsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.field', 'app.post', 'app.post_type', 'app.user', 'app.category', 'app.comment');

	function startTest() {
		$this->Fields =& new TestFieldsController();
		$this->Fields->constructClasses();
	}

	function endTest() {
		unset($this->Fields);
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