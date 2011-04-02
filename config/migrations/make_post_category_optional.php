<?php
class M4d915855b8904bf490956d86fb8c9e6b extends CakeMigration {

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
			'alter_field' => array(
				'posts' => array(
					'category_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'posts' => array(
					'category_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
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