<?php
class M4d894b2202f44b878ef915d4fb8c9e6b extends CakeMigration {

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
			'create_field' => array(
				'users' => array(
					'post_count' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
				),
			),
			'drop_field' => array(
				'users' => array('blog_count',),
			),
		),
		'down' => array(
			'drop_field' => array(
				'users' => array('post_count',),
			),
			'create_field' => array(
				'users' => array(
					'blog_count' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
				),
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