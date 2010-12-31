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
			'dashboard_login' => 'visitor',
			'dashboard_logout' => 'user'
		),
		'Comments' => array(
			'add' => 'visitor',
			'dashboard_index' => 'user',
			'dashboard_index' => 'user',
		),
		'Categories' => array(
			'view' => 'visitor',
			'dashboard_index' => 'user',
			'dashboard_view' => 'user',
			'dashboard_add' => 'user',
			'dashboard_edit' => 'user',
			'delete' => 'user'
		),
		'Settings' => array(
			'dashboard_index' => 'user',
			'dashboard_menu' => 'user',
			'dashboard_add_to_menu' => 'user',
			'dashboard_remove_from_menu' => 'user',
			'dashboard_change_theme' => 'user'
		)
	);
}
