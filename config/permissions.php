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
			'dashboard_delete'  => 'user'
		),
		'Users' => array(
			'dashboard_login' => 'visitor',
			'dashboard_logout' => 'user'
		)
	);
}
