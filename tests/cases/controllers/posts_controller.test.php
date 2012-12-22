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
		$expectedPost = $this->Posts->Post->find('first');
		$results = $this->testAction(
			'/post/' . $expectedPost['Post']['id'] . '-' . $expectedPost['Post']['slug'],
			array('return' => 'vars')
		);
		$this->assertEqual($expectedPost, $results['post']);
	}

	function testAdd() {

	}

	/**
	 * Test if that no new field rows are created in the database when editing the current ones.
	 *
	 **/
	function testEditNoNewFields() {
		$post = $this->Posts->Post->find('first');
		$fieldsCount = count($post['Field']);
		$data = array();
		foreach ($post['Field'] as $field) {
			$fieldName = $field['name'];
			if (strstr($fieldName, '_image')) {
				$field['value'] = array(
                    'name' => '', 'type' => '',
                    'tmp_name' => '',
                    'error' => 4,
                    'size' => 0
                );
			}
			$data['Post'][$field['name']] = $field['value'];
		}
		$data['Post']['category_id'] = 1;
		$data['Post']['id'] = $data['Post']['guide'] = $post['Post']['id'];
		$data['Post']['draft'] = 0;
		$data['Post']['published'] = array(
			'year' => 2012,
			'month' => 12,
			'day' => 18,
			'hour' => 14,
			'min' => 27,
		);
		list($plugin, $type) = explode('.', $post['Post']['type']);
		$url = Router::url(
			array(
				'controller' => 'test_posts',
				'action' => 'edit',
				'dashboard' => true,
				'prefix' => 'dashboard',
				$plugin, $type,
				'id' => $post['Post']['id']
			)
		);
		$url = substr($url, strlen(Router::url('/')) -1);
		$results = $this->testAction(
			$url,
			array('return' => 'result', 'data' => $data)
		);
		$postAfter = $this->Posts->Post->find('first');
		$this->assertEqual($fieldsCount, count($postAfter['Field']));
	}

	function testDelete() {

	}

	public function testRoutes()
	{
		$this->assertEqual(
			Router::url(array(
				'controller' => 'posts', 'action' => 'view',
				'id' => '50d12490-1390-4a7e-97de-1439fb8c9e6b', 'slug' => 'ish', 'type' => 'Some.Type',
				'dashboard' => false
			)),
			Router::url('/') . 'post/50d12490-1390-4a7e-97de-1439fb8c9e6b-ish'
		);
	}
}
?>