<?php echo $this->Blogmill->postEditLink($post, null, array('class' => 'journal')); ?>
<h1><?php echo $this->Blogmill->postLink($post); ?></h1>
<div id="post-content" class="journal">
	<?php
		$img = $this->Blogmill->image($post, 'home_showcase', array('class' => 'showcase-image'));
		echo $img . $this->Blogmill->field($post, 'content');
	?>
</div>