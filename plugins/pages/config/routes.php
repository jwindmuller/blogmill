<?php
Router::connect(
	'/page/:id-:slug/*',
	array('controller' => 'posts', 'action' => 'view', 'type' => 'Pages.Page'),
	array(
		'pass' => array('id', 'slug'),
		'id' => '[0-9]+'
	)
);