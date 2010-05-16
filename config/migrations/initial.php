<?php
class M4bf043c73b0442be87a34428fb8c9e6b extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'categories' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'title' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150),
					'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150),
					'category_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM'),
				),
				'comments' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'post_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 60),
					'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 180),
					'url' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 180),
					'content' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM'),
				),
				'fields' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
					'post_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 90),
					'value' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM'),
				),
				'post_types' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'original' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 160),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 160),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM'),
				),
				'posts' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 80),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'category_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM'),
				),
				'users' => array(
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
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'categories', 'comments', 'fields', 'post_types', 'posts', 'users'
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}
}
?>