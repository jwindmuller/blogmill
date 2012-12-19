<?php
/* Posts Test cases generated on: 2010-05-12 21:05:16 : 1273720876*/
App::import('Controller', 'Posts');

class TestPostsController extends PostsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class PostsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.post', 'app.post_type', 'app.user', 'app.category', 'app.comment', 'app.field', 'app.attachment', 'app.setting');

	function startTest() {
		// Include our custom routes to test them
		include CONFIGS . 'routes.php';
		$this->Posts =& new TestPostsController();
		$this->Posts->constructClasses();
		$this->Posts->beforeFilter();
	}

	function endTest() {
		unset($this->Posts);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testView() {
		$expectedPost = $this->Posts->Post->read(null, '50d12490-1390-4a7e-97de-1439fb8c9e6b');
		$results = $this->testAction('/post/50d12490-1390-4a7e-97de-1439fb8c9e6b-lorem-ipsum', array('return' => 'vars'));
		$this->assertEqual($expectedPost, $results['post']);
	}

	function testAdd() {

	}

	function testEdit() {

	}

	function testDelete() {

	}

	public function testRoutes()
	{
		$this->assertEqual(
			Router::url(array(
				'controller' => 'posts', 'action' => 'view',
				'id' => '50d12490-1390-4a7e-97de-1439fb8c9e6b', 'slug' => 'ish', 'type' => 'Some.Type'
			)),
			Router::url('/') . 'post/50d12490-1390-4a7e-97de-1439fb8c9e6b-ish'
		);
	}
}
?>