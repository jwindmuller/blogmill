<?php
class Category extends AppModel {
	var $name = 'Category';
	var $actsAs = array('Sluggable' => array('overwrite' => true, 'translation' => 'utf-8'));
	
	var $validate = array(
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			)
		),
		'slug' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			)
		),
		'category_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'allowEmpty' => true,
				'required' => false,
			)
		),
	);

	var $belongsTo = array(
		'ParentCategory' => array(
			'className' => 'Category',
			'foreignKey' => 'category_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $hasMany = array(
		'SubCategory' => array(
			'className' => 'Category',
			'foreignKey' => 'category_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Post' => array(
			'className' => 'Post',
			'foreignKey' => 'category_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
?>