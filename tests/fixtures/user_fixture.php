<?php
/* User Fixture generated on: 2010-05-12 21:05:35 : 1273721135 */
class UserFixture extends CakeTestFixture {
	var $name = 'User';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'username' => array('type' => 'string', 'null' => false, 'length' => 140),
		'alias' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 140),
		'password' => array('type' => 'string', 'null' => false),
		'email' => array('type' => 'string', 'null' => false, 'length' => 140),
		'blog_count' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
		'admin' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'profile' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'url' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'confirmation' => array('type' => 'string', 'null' => false),
		'question' => array('type' => 'string', 'null' => false),
		'answer' => array('type' => 'string', 'null' => false),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'username' => 'Lorem ipsum dolor sit amet',
			'alias' => 'Lorem ipsum dolor sit amet',
			'password' => 'Lorem ipsum dolor sit amet',
			'email' => 'Lorem ipsum dolor sit amet',
			'blog_count' => 1,
			'admin' => 1,
			'created' => '2010-05-12 21:25:35',
			'modified' => '2010-05-12 21:25:35',
			'profile' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'url' => 'Lorem ipsum dolor sit amet',
			'confirmation' => 'Lorem ipsum dolor sit amet',
			'question' => 'Lorem ipsum dolor sit amet',
			'answer' => 'Lorem ipsum dolor sit amet'
		),
	);
}
?>