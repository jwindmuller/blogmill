<?php
/* Post Test cases generated on: 2010-05-12 21:05:15 : 1273720875*/
App::import('Model', 'Post');

class PostTestCase extends CakeTestCase {
	var $fixtures = array('app.post', 'app.user', 'app.category', 'app.comment', 'app.field');

	function startTest() {
		$this->Post =& ClassRegistry::init('Post');
	}

	function endTest() {
		unset($this->Post);
		ClassRegistry::flush();
	}
	
	public function testNewPost() {
		$this->Post->deleteAll(array("1=1"));
		$expected = $data = array(
			'Post' => array(
				'type' => 'journal',
				'title' => 'test',
				'content' => 'test\'s content'
			)
		);
		$expected['Post']['id'] = '1';
		$expected['Post']['user_id'] = '0';
		$expected['Post']['category_id'] = '0';
		$expected['Field'] = array(
			array(
				'post_id' => '1',
				'name' => 'title',
				'value' => 'test'
			),
			array(
				'post_id' => '1',
				'name' => 'content',
				'value' => 'test\'s content'
			)
		);
		$this->Post->create();
		$this->Post->savePost($data);
		$this->Post->contain('Field');
		$post = $this->Post->find('first');
		unset($post['Post']['created'], $post['Post']['modified']);
		unset($post['Field'][0]['id'], $post['Field'][1]['id']);
		$this->assertEqual($post, $expected);
		
		$new_data = array(
			'Post' => array(
				'id' => '1',
				'title' => 'title renamed',
				'content' => 'content renamed'
			)
		);
		$expected['Post']['title'] =
		$expected['Field'][0]['value'] = 'title renamed';
		$expected['Post']['content'] =
		$expected['Field'][1]['value'] = 'content renamed';
		$this->Post->savePost($new_data);
		$this->Post->contain('Field');
		$post = $this->Post->find('first');
		unset($post['Post']['created'], $post['Post']['modified']);
		unset($post['Field'][0]['id'], $post['Field'][1]['id']);
		$this->assertEqual($this->Post->Field->find('count'), 2);
		$this->assertEqual($post, $expected);
	}

}
?>