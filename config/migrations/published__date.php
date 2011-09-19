<?php
class M4e72b5f3e99444808549475dfb8c9e6b extends CakeMigration {

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
					'published' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'posts' => array('published',),
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
		if ( $direction == 'up' ) {
			$Post = ClassRegistry::init('Post');
			$posts = $Post->find('all');
			foreach ($posts as $i => $post) {
				$draft = $post['Post']['draft'];
				if ( $draft == 0) {
					$m = $post['Post']['modified']; 
					$post['Post']['published'] = $m;
					$Post->id = $post['Post']['id'];
					$Post->saveField( 'published', $m, false);
				}
			}
		}
		return true;
	}
}