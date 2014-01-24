<?php
class M4e4547fdaf04408f8808e458fb8c9e6b extends CakeMigration {

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
				'posts' => array(
					'draft' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
				),
			),
			'drop_table' => array(
				'post_types'
			),
		),
		'down' => array(
			'drop_field' => array(
				'posts' => array('draft',),
			),
			'create_table' => array(
				'post_types' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'original' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 160, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 160, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM'),
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