<?php

App::import('Lib', 'routes/BlogmillRouter');
App::import('Core', array('Router'));
if (!defined('FULL_BASE_URL')) {
	define('FULL_BASE_URL', 'http://cakephp.org');
}

class BlogmillRouterTestCase extends CakeTestCase {
	/**
	 * setUp method
	 *
	 * @access public
	 * @return void
	 */
	function setUp() {
		$this->_routing = Configure::read('Routing');
		
		Router::reload();
		App::import('File', 'Routes', array('file' => APP . 'config' . DS . 'routes.php'));
		// Configure::write('Routing', array('admin' => null, 'prefixes' => array()));
		$this->router =& Router::getInstance();
		App::import('File', 'Routes', array('file' => APP . 'config' . DS . 'routes.php'));
	}
	
	public function testParse() {
		$result = $this->router->url(array('controller' => 'posts', 'action' => 'view', 'id' => '1', 'slug' => 'hi', 'type' => 'Cosos'));
		debug($result);
	}
}