<?php
	class BlogrollSettings extends Settings {
		var $types = array(
			'Link' => array(
				'fields' => array(
					'title' => 'text',
					'description' => 'longtext',
					'url' => 'text'
				),
				'form_layout' => array(
					'rows' => array(
						array(
							array('width' => 'auto', 'fields' => array('url', 'title', 'description')),
						)
					)
				),
				'display' => 'title'
			)
		);
	}