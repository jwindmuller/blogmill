<?php
/* Category Fixture generated on: 2010-05-12 21:05:03 : 1273721103 */
class CategoryFixture extends CakeTestFixture {
	var $name = 'Category';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150),
		'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150),
		'category_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'title' => 'Lorem ipsum dolor sit amet',
			'slug' => 'Lorem ipsum dolor sit amet',
			'category_id' => 1
		),
	);
}
?>