<?php
class M4c05bffdaca045c19e1e4ebafb8c9e6b extends CakeMigration {

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
					'display' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'slug' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'guide' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'posts' => array('display', 'slug', 'guide')
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