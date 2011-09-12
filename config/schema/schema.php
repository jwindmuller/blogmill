<?php 
/* SVN FILE: $Id$ */
/* App schema generated on: 2011-08-12 15:08:20 : 1313163380*/
class AppSchema extends CakeSchema {
	var $name = 'App';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $acos = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'alias' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
	var $aros = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'alias' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
	var $aros_acos = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'aro_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'aco_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
		'_create' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'_read' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'_update' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'_delete' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'ARO_ACO_KEY' => array('column' => array('aro_id', 'aco_id'), 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
	var $categories = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'category_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM')
	);
	var $comments = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'post_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 60, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 180, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'url' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 180, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'content' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM')
	);
	var $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'post_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 90, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'value' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM')
	);
	var $posts = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 80, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'category_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'display' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'guide' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'excerpt' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'draft' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM')
	);
	var $settings = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'value' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
	var $users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'username' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 140, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 140, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'profile' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'url' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'confirmation' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'question' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'answer' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'post_count' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 140, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM')
	);
}
?>