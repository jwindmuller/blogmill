<?php
// echo $this->Blogmill->postLink($post);
echo $this->Html->link(
	'jsdkfjbsdjfksbfjbsdkfbksjdåo',
	array(
		'controller' => 'posts',
		'action' => 'view',
		'id' => $post['Post']['id'],
		'slug' => $post['Post']['display'],
		'type' => 'Pages.Page'
	)
);
// debug($post);
?>