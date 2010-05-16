<?php
class Journal extends JournalAppModel {
	var $useTable = false;
	
	var $validate = array(
		'title' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => ''
			),
			'maxLength' => array(
				'rule' => array('maxLength', 255),
				'message' => 'maxLengthi'
			)
		),
		'content' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'bu'
			)
		)
	);
	
	var $types = array(
		'title' => 'text',
		'content' => 'textarea'
	);
	
}