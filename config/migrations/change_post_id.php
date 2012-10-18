<?php
class M50801b5758ac4282af973744fb8c9e6b extends CakeMigration {

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
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
				),
				'comments' => array(
					'post_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
				),
				'fields' => array(
					'post_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
				),
				'attachments' => array(
					'post_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'posts' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
				),
				'comments' => array(
					'post_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
				),
				'fields' => array(
					'post_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
				),
				'attachments' => array(
					'post_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
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