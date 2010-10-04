<?php
	class PagesSettings extends Settings {
		public $types = array(
			'Page' => array(
				'fields' => array(
					'title' => 'text',
					'content' => 'html'
				),
				'form_layout' => array(
					'rows' => array(
						array(
							array('fields' => array('title', 'content'))
						)
					)
				),
				'display' => 'title',
				'comments' => false
			)
		);
		
	}
	