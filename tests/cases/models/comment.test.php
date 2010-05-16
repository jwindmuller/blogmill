<?php
/* Comment Test cases generated on: 2010-05-12 21:05:08 : 1273721108*/
App::import('Model', 'Comment');

class CommentTestCase extends CakeTestCase {
	var $fixtures = array('app.comment', 'app.post', 'app.post_type', 'app.user', 'app.category', 'app.field');

	function startTest() {
		$this->Comment =& ClassRegistry::init('Comment');
	}

	function endTest() {
		unset($this->Comment);
		ClassRegistry::flush();
	}

}
?>