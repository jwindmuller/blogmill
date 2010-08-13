<?php
class Journal extends BookReviewsAppModel {
	var $useTable = false;
	
	var $validate = array(
		'title' => array(
			'required' => array(
				'rule' => array('notEmpty')
			),
			'maxLength' => array(
				'rule' => array('maxLength', 255)
			)
		),
		'content' => array(
			'required' => array(
				'rule' => array('notEmpty')
			)
		),
		'home_showcase' => array(
			'valid_image' => array(
				'rule' => 'validateImage',
				'on' => 'create',
				'required' => true
			),
			'valid_image_update' => array(
				'rule' => 'validateImage',
				'on' => 'update',
				'required' => false
			)
		)
	);
	
	/**
	 * Initialize Validation
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	protected function __initializeValidation() {
		$this->defineErrorMessage('title.required', __('The title is required', true));
		$this->defineErrorMessage('title.maxLength', __('Maximum length is 255 character', true));
		$this->defineErrorMessage('content.required', __('Write the content!', true));
	}
}