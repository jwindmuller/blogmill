<?php
	Router::parseExtensions('json');
	
	App::import('Lib', 'routes/BlogmillUnmatchedRoute');

	$plugins = Configure::listObjects('plugin');
	foreach ($plugins as $i => $plugin) {
		$file = "{$plugin}Routes";
		App::import(
			array(
				'type' => 'File',
				'name' => $file,
				'file' => APP . 'plugins' . DS . Inflector::underscore($plugin) . DS . 'config' . DS . 'routes.php'
			)
		);
	}
	
	/* Dashboard Routes */
	Router::connect(
		'/dashboard/posts/add/:plugin_name::post_type',
		array('controller' => 'posts', 'action' => 'add', 'prefix' => 'dashboard', 'dashboard' => true),
		array('pass' => array('plugin_name', 'post_type'))
	);
	Router::connect(
		'/dashboard/posts/edit/:id',
		array('controller' => 'posts', 'action' => 'edit', 'prefix' => 'dashboard', 'dashboard' => true),
		array('pass' => array('plugin_name', 'post_type'))
	);
	Router::connect(
		'/dashboard',
		array('controller' => 'posts', 'action' => 'index', 'prefix' => 'dashboard', 'dashboard' => true)
	);
	/* Site Routes */
	Router::connect(
		'/:type',
		array('controller' => 'posts', 'action' => 'index'),
		array('pass' => array('type'))
	);
	Router::connect('/', array('controller' => 'posts', 'action' => 'home'));
	Router::connect(
		'/post/:id-:slug/*',
		array('controller' => 'posts', 'action' => 'view', 'type' => null),
		array(
			'pass' => array('id', 'slug', 'type'),
			'id' => '[0-9]+',
			'slug' => '.+',
			'type' => '.*',
			'routeClass' => 'BlogmillUnmatchedRoute'
		)
	);
?>