<div class="book-review post-index-preview">
	<?php echo $this->Blogmill->postEditLink($post); ?>
	<h1>
		<?php
			$last_char = substr($post['Post']['author'], -1, 1);
			$pattern = __(':author\'s', true);
			if ($last_char == 's') {
				$pattern = __(':author\'', true);
			}
		?>
		<span><?php echo String::insert($pattern, array('author' => $post['Post']['author'])); ?></span>
		<?php echo $this->Blogmill->postLink($post); ?>
	</h1>
	<?php
		echo $this->Blogmill->postLink($post,
			array('display' => $this->Blogmill->image($post, 'home_small')),
			array('escape' => false)
		);
	?>
	<p>
		<?php echo $this->Blogmill->excerpt($post, 500); ?>
		<?php
			echo $this->Blogmill->postLink($post,
				array('display' => __('Read this review...', true)),
				array('class' => 'call-to-action-link')
			);
		?>
	</p>
</div>