<h1><?php echo $post['Post']['title']; ?></h1>
<div id="post-" class="post">
	<?php echo $post['Post']['content']; ?>
	<p>
		<?php
			$category_link = $html->link($post['Category']['title'], array('controller' => 'categories', 'action' => 'view', $post['Category']['slug']));
			printf(__('Published under %s', true), $category_link);
		?> | 
		<?php
			echo $html->link(
				__('Continue reading...', true),
				array('controller' => 'posts', 'action' => 'view', 'id' => $post['Post']['id'], 'slug' => $post['Post']['slug']),
				array('class' => 'more')
			);
		?>
	</p>
</div>