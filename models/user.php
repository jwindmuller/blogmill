<?php
class User extends AppModel {
	var $name = 'User';
	var $actsAs = array('Acl' => array('type' => 'requester'));
	
	var $validate = array(
		'username' => array(
			'required' => array(
				'rule' => 'notempty',
				'last' => true
			),
			'unique' => array(
				'rule' => 'validateUnique',
				'required' => true,
				'on'	=> 'create'
			),
			'startsWith' => array(
				'rule' => array('custom', '/^[a-zA-Z0-9].*$/'),
				'last' => true
			),
			'minlength'	=> array(
				'rule'	=> array('minlength', 3),
				'last'	=> true
			),
			'valid' => array(
				'rule' => array('validateUsername')
			)
		),
		'email' => array(
			'required' => array(
				'rule' => 'notempty',
				'last' => true
			),
			'valid' => array(
				'rule' => 'email',
				'last' => true
			),
			'unique' => array(
				'rule' => array('validateUnique')
			)
		),
		'password' =>  array(
			'requiredOnUpdate' => array(
				'rule' => array('passwordNotEmpty'),
				'last' => true,
				'required' => false,
				'on' => 'update'
			),
			'confirmationOnUpdate' => array(
			 	'rule' => array('confirmPassword'),
			 	'on' => 'update',
			 	'required' => false
			)
		),
		'password_confirm' => array(
			'minlengthOnUpdate' => array(
				'rule' => array('minLength', '6'),
				'last' => true,
				'on' => 'update',
				'required' => false
			)
		),
		'question' => array(
			'required' => array(
				'rule' => 'notempty',
				'required' => false,
				'last' => true
			),
		),
		'answer' => array(
			'required' => array(
				'rule' => 'notempty',
				'required' => false,
				'last' => true
			),
		)
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $hasMany = array(
		'Post' => array(
			'className' => 'Post',
			'foreignKey' => 'user_id',
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
	
function __initializeValidation() {
		$this->defineErrorMessage(
			'username.required',
			__('Write your username',true)
		);
		$this->defineErrorMessage(
			'username.unique',
			__('This username is taken',true)
		);
		$this->defineErrorMessage(
			'username.startsWith',
			__('Must start with a letter or a number',true)
		);
		$this->defineErrorMessage(
			'username.minlength',
			__('Must be at least 3 characters long',true)
		);
		$this->defineErrorMessage(
			'username.valid',
			__('Only numbers, letters, underscore (_) and dots (.) allowed',true)
		);
		$this->defineErrorMessage(
			'email.valid',
			__('Write a valid email address',true)
		);
		$this->defineErrorMessage(
			'email.required',
			__('Write your email address',true)
		);
		$this->defineErrorMessage(
			'email.unique',
			__('Email already registered',true)
		);
		$this->defineErrorMessage(
		    'password.requiredOnUpdate',
		    __('Write your password',true)
		);
		$this->defineErrorMessage(
			'password.confirmationOnUpdate',
			__('The password and its confirmation do not match',true)
		);
		$this->defineErrorMessage(
			'password_confirm.minlengthOnUpdate',
			__('Passwords are required to have at least 6 characters',true)
		);
		$this->defineErrorMessage(
			'question.required',
			__('The secret question is required',true)
		);
		$this->defineErrorMessage(
			'answer.required',
			__('Please set reply the secret question',true)
		);
		$this->_findMethods = $this->_findMethods + array('usernameOrEmail' => true);
	}
	
	public function validateUsername($data) {
		$username = $data['username'];
		$match_chars = preg_match('/^[a-zA-Z0-9][a-zA-Z0-9._]{2,}$/', $username);
		if (!$match_chars) return false;
		$match_chars = preg_match('/^[._]+$/', $username);
		if ($match_chars) return false;
		return true;
	}
	
	public function passwordNotEmpty($data) {
		if (!isset($data['password'])) return true;
		return $data['password'] != Security::hash(Configure::read('Security.salt') . '');
	}
	
	/**
	 * Validation function to check if the password and its confirmation are the same.
	 *
	 * @param array $data data sent
	 * @return boolean true if the passwords are the same
	 */
	function confirmPassword($data) {
		$valid = false;
        debug($this->data);
		if ($data['password'] == Security::hash(Configure::read('Security.salt') . $this->data['User']['password_confirm'])) {
		   $valid = true;
		}
		return $valid;
	}
	
	public function customMinLength($data, $length) {
		return (strlen($this->data['User']['password_confirm']) >= $length);
	}
	
	public function beforeValidate($model) {
		if ($this->exists() && !$this->passwordNotEmpty($this->data['User'])) {
			unset($this->data['User']['password']);
			unset($this->data['User']['password_confirm']);
		}
		$currentQuestion = $this->field('question');
		$currentAnswer = $this->field('answer');
		if ($this->exists() && !empty($currentQuestion) && !empty($currentAnswer) && !empty($this->data['User']['question']) && empty($this->data['User']['answer'])) {
			unset($this->data['User']['question']);
			unset($this->data['User']['answer']);
		}
		return true;
	}

	function parentNode() {
		if (!$this->id && empty($this->data)) {
			return null;
		}
		if (isset($this->data['User']['admin'])) {
			return 'admin';
		}
		return 'user';
	}
}
?>
