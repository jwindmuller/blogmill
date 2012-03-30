<?php
/**
* This class contains the permission definitions for controller/action ACL
*/
class BlogmillPermissions {
	public $definitions = array(
		'Posts' => array(
			'home' => 'visitor',
			'index' => 'visitor',
			'view' => 'visitor',
			'dashboard_index' => 'user',
			'dashboard_add' => 'user',
			'dashboard_edit' => 'user',
			'dashboard_delete'  => 'user',
			'dashboard_list'  => 'user'
		),
		'Users' => array(
            'dashboard_index' => 'user',
            'dashboard_view' => 'user',
            'dashboard_edit' => 'user',
            'dashboard_add' => 'user',
			'dashboard_login' => 'visitor',
            'dashboard_activate' => 'visitor',
            'dashboard_recover' => 'visitor',
			'dashboard_logout' => 'user',
            'dashboard_notify' => 'user'
		),
		'Comments' => array(
			'add' => 'visitor',
			'dashboard_index' => 'user',
			'dashboard_index' => 'user',
            'dashboard_approve' => 'user',
            'dashboard_spam' => 'user',
		),
		'Categories' => array(
			'view' => 'visitor',
			'dashboard_index' => 'user',
			'dashboard_view' => 'user',
			'dashboard_add' => 'user',
			'dashboard_delete' => 'user',
			'dashboard_edit' => 'user',
		),
        'Contacts' => array(
            'send' => 'visitor'
        ),
		'Settings' => array(
			'dashboard_index' => 'user',
			'dashboard_menu' => 'user',
			'dashboard_menu_change_item' => 'user',
			'dashboard_add_to_menu' => 'user',
			'dashboard_remove_from_menu' => 'user',
			'dashboard_change_theme' => 'user',
            'dashboard_plugins' => 'user',
            'dashboard_get_index_url' => 'user'
		),
        'Plugins' => array(
            'dashboard_page' => 'user'
        ),
        'Pages' => array(
            'display' => 'visitor',
            'favicon' => 'visitor'
        )
    );
}
