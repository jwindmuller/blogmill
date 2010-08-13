<?php
class Comment extends AppModel {
	var $name = 'Comment';
	var $validate = array(
		'post_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'allowEmpty' => false,
				'required' => true
			),
		),
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'email' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'last' => true
			),
			'valid' => array(
				'rule' => array('email')
			),
		),
		'url' => array(
			'valid' => array(
				'rule' => array('url'),
				'allowEmpty' => true
			)
		),
		'content' => array(
			'notempty' => array(
				'rule' => array('notempty')
			)
		)
	);

	var $belongsTo = array(
		'Post' => array(
			'className' => 'Post',
			'foreignKey' => 'post_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	/**
	 * Initialize Validation
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	protected function __initializeValidation() {
		$this->defineErrorMessage('post_id.numeric', __('Post ID not found', true));
		$this->defineErrorMessage('name.notempty', __('Who are you?', true));
		$this->defineErrorMessage('email.notempty', __('Please tell us your email', true));
		$this->defineErrorMessage('email.valid', __('This is not a valid email', true));
		$this->defineErrorMessage('url.valid', __('This is not a valid web address', true));
		$this->defineErrorMessage('content.notempty', __('Your comment?', true));
	}
}
?>