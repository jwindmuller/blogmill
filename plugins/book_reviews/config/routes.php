<?php
Router::connect(
	'/book-reviews',
	array('controller' => 'posts', 'action' => 'index', 'type' => 'BookReviews'),
	array('pass' => array('type'))
);
Router::connect(
	'/notepad',
	array('controller' => 'posts', 'action' => 'index', 'type' => 'Journal'),
	array('pass' => array('type'))
);
Router::connect(
	'/book-review/:id-:slug/*',
	array('controller' => 'posts', 'action' => 'view', 'type' => 'BookReviews.BookReview'),
	array(
		'pass' => array('id', 'slug'),
		'id' => '[0-9]+'
	)
);
