<?php
class M4d894ed8089c487ea1b51cb7fb8c9e6b extends CakeMigration {

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
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 140, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
				),
			),
			'drop_field' => array(
				'users' => array('alias',),
			),
		),
		'down' => array(
			'drop_field' => array(
				'users' => array('name',),
			),
			'create_field' => array(
				'users' => array(
					'alias' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 140, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
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