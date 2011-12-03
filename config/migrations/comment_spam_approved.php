<?php
class M4ed94b0e10484593afda05e8fb8c9e6b extends CakeMigration {

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
				'comments' => array(
					'approved' => array('type' => 'boolean', 'null' => false, 'default' => NULL, 'after' => 'created'),
					'spam' => array('type' => 'boolean', 'null' => false, 'default' => NULL, 'after' => 'approved'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'comments' => array('approved', 'spam',),
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