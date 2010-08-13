<?php
class Link extends BlogrollAppModel {
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
		'description' => array(
			'maxLength' => array(
				'rule' => array('maxLength', 255)
			)
		),
		'url' => array(
			'required' => array(
				'rule' => array('notEmpty')
			),
			'url' => array(
				'rule' => array('url')
			),
			'maxLength' => array(
				'rule' => array('maxLength', 255)
			)
		),
	);
	
	/**
	 * Initialize Validation
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	protected function __initializeValidation() {
		$this->defineErrorMessage('title.required', __('The title is required', true));
		$this->defineErrorMessage('title.maxLength', __('Maximum length is 255 characters', true));
		$this->defineErrorMessage('description.maxLength', __('Maximum length is 255 characters', true));
		$this->defineErrorMessage('url.required', __('The url is required', true));
		$this->defineErrorMessage('url.maxLength', __('Maximum length is 255 characters', true));
		$this->defineErrorMessage('url.url', __('This is not a valid url', true));
	}
}