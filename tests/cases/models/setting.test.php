<?php
/* Setting Test cases generated on: 2010-08-14 13:08:56 : 1281814736*/
App::import('Model', 'Setting');

class SettingTestCase extends CakeTestCase {
	var $fixtures = array('app.setting');

	function startTest() {
		$this->Setting =& ClassRegistry::init('Setting');
	}

	function endTest() {
		unset($this->Setting);
		ClassRegistry::flush();
	}

}
?>