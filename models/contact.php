<?php
class Contact extends AppModel {
	public $useTable = false;

   	var $validate = array(
		'name' => array(
			'required' => array(
				'rule' => array('notEmpty')
			),
			'maxLength' => array(
				'rule' => array('maxLength', 255)
			)
		),
        'email' => array(
			'required' => array(
				'rule' => array('notEmpty')
			),
            'valid_email' => array(
                'rule' => 'email'
            ),
		),
        'subject' => array(
			'required' => array(
				'rule' => array('notEmpty')
			),
			'maxLength' => array(
				'rule' => array('maxLength', 255)
			)
		),
        'message' => array(
			'required' => array(
				'rule' => array('notEmpty')
			),
		),
	);
	
	/**
	 * Initialize Validation
	 *
	 * @return void
	 * @author Joaquin Windmuller
	 */
	protected function __initializeValidation() {
		$this->defineErrorMessage('name.required', __('Please write your name', true));
        $this->defineErrorMessage('name.maxLength', __('Maximum length is 255 character', true));
		$this->defineErrorMessage('email.required', __('Please write your email', true));
        $this->defineErrorMessage('email.valid_email', __('That email is not valid', true));
		$this->defineErrorMessage('subject.required', __('Please write the subject of your message', true));
        $this->defineErrorMessage('name.maxLength', __('Maximum length is 255 character', true));
		$this->defineErrorMessage('message.required', __('Please write your message', true));
	}

}
