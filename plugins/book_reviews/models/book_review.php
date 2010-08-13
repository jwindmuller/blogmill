<?php
class BookReview extends BookReviewsAppModel {
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
		'author' => array(
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
				'rule' => array('validateImage', 430, 290),
				'on' => 'create',
				'required' => true
			),
			'valid_image_update' => array(
				'rule' => 'validateImage',
				'on' => 'update',
				'required' => false
			)
		),
		'home_small' => array(
			'valid_image' => array(
				'rule' => array('validateImage', 128, 128),
				'on' => 'create',
				'required' => true
			),
			'valid_image_update' => array(
				'rule' => array('validateImage', 128, 128),
				'on' => 'update',
				'required' => false
			)
		),
		'book_cover' => array(
			'valid_image' => array(
				'rule' => array('validateImage', 300),
				'on' => 'create',
				'required' => true
			),
			'valid_image_update' => array(
				'rule' => array('validateImage', 300),
				'on' => 'update',
				'required' => false
			)
		),
		'evaluation_fun' => array(
			'values' => array(
				'rule' => array('between', 0,5)
			),
		),
		'evaluation_interesting' => array(
			'values' => array(
				'rule' => array('between', 0,5)
			),
		),
		'evaluation_characters' => array(
			'values' => array(
				'rule' => array('between', 0,5)
			),
		),
		'evaluation_ending' => array(
			'values' => array(
				'rule' => array('between', 0,5)
			),
		),
		'evaluation_overall' => array(
			'values' => array(
				'rule' => array('between', 0,5)
			),
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
		$this->defineErrorMessage('title.maxLength', __('Maximum length is 255 characters', true));
		$this->defineErrorMessage('author.required', __('The author of the book is required', true));
		$this->defineErrorMessage('author.maxLength', __('Maximum length is 255 characters', true));
		$this->defineErrorMessage('content.required', __('Write the content!', true));
		$this->defineErrorMessage('home_small.valid_image', __('This is not a valid image!', true));
		$this->defineErrorMessage('home_small.valid_image_update', __('This is not a valid image!', true));
		$this->defineErrorMessage('home_showcase.valid_image', __('This is not a valid image!', true));
		$this->defineErrorMessage('home_showcase.valid_image_update', __('This is not a valid image!', true));
		$this->defineErrorMessage('book_cover.valid_image', __('This is not a valid image!', true));
		$this->defineErrorMessage('book_cover.valid_image_update', __('This is not a valid image!', true));
	}
}