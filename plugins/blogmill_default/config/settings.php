<?php
class BlogmillDefaultSettings extends BlogmillSettings {
    var $types = array();
    var $configurable = array(
        'blogmill_site_name' => array(
            'label' => 'Website name'
        ),
		'blogmill_contact_email' => array(
			'label' => 'Contact email',
			'longdesc' => 'Write the contact email where you want to receive emails.'
		),
	);
    var $theme = array(
		'name' => 'Blogmill Default Theme',
		'description' => 'Bare bones theme',
		'author' => 'Windmill Information Technology Inc.',
		'author_url' => 'http://windmuller.ca',
		'version' => '1.0',
		'menus' => array(),
		'layouts' => array(
			'home' => array(
				'name' => 'default',
				'load_menus' => array(),
				'data' => array(
					'posts' => array(
						'type' => '*',
						'limit' => 10,
						'order' => 'Post.created DESC'
					)
				)
			),
			'inner' => array(
				'name' => 'default',
				'load_menus' => array() 
			)
		),
		'post_type_decorators' => array()
	);
}
