<?php
	class BookReviewsSettings extends Settings {
		var $types = array(
			'BookReview' => array(
				'fields' => array(
					'title' => 'text',
					'author' => array(
						'type' => 'text',
						'autocomplete' => true
					),
					'content' => 'html',
					'home_small' => array(
						'type' => 'image',
						'width' => '128',
						'height' => '128',
						'description' => 'Small thumbnail for home'
					),
					'home_showcase' => array(
						'type' => 'image',
						'width' => '430',
						'height' => '290',
						'description' => 'Large image for showcase'
					),
					'book_cover' => array(
						'type' => 'image',
						'width' => '300',
						'description' => 'Book cover for review content'
					),
					'evaluation_fun' => array(
						'type' => 'values',
						'count' => 5,
						'description' => 'Was it fun to read?'
					),
					'evaluation_interesting' => array(
						'type' => 'values',
						'count' => 5,
						'description' => 'Kept me interested?',
					),
					'evaluation_characters' => array(
						'type' => 'values',
						'count' => 5,
						'description' => 'what about the characters?',
					),
					'evaluation_ending' => array(
						'type' => 'values',
						'count' => 5,
						'description' => 'it had a good ending?',
					),
					'evaluation_overall' => array(
						'type' => 'values',
						'count' => 5,
						'description' => 'Overall rating',
					)
				),
				'form_layout' => array(
					'rows' => array(
						array(
							array('width' => 'auto', 'fields' => array('title', 'author', 'content')),
							array('width' => '250px', 'fields' => array('home_small', 'home_showcase', 'book_cover', 'evaluation_fun', 'evaluation_interesting', 'evaluation_characters', 'evaluation_ending', 'evaluation_overall', 'category'))
						)
					)
				),
				'display' => 'title'
			),
			'Journal' => array(
				'fields' => array(
					'title' => 'text',
					'content' => 'html',
					'home_showcase' => array(
						'type' => 'image',
						'width' => '430',
						'height' => '290',
						'description' => 'Large image for showcase'
					)
				),
				'form_layout' => array(
					'rows' => array(
						array(
							array('width' => 'auto', 'fields' => array('title', 'content')),
							array('width' => '250px', 'fields' => array('home_showcase'))
						)
					)
				),
				'display' => 'title'
			)
		);
		
		var $theme = array(
			'name' => 'Mary Windmuller',
			'version' => '1.0',
			'menus' => array('top_menu' => 'Top Menu'),
			'layouts' => array(
				'prefix' => 'mw',
				'home' => array(
					'name' => 'default',
					'load_menus' => array('top_menu'),
					'data' => array(
						'books' => array(
							'type' => 'BookReviews.BookReview',
							'limit' => 4,
							'order' => 'Post.created DESC'
						),
						'latest_post' => array(
							'type' => 'BookReviews.Journal',
							'limit' => 1
						),
						'blogroll' => array(
							'type' => 'Blogroll.Link',
							'limit' => 5
						)
					)
				),
				'inner' => array(
					'name' => 'inner',
					'load_menus' => array('top_menu') 
				)
			),
		);
	}
?>