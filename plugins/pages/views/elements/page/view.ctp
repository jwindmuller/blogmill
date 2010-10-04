<h1><?php echo $this->Blogmill->field($post, 'title'); ?></h1>
<div id="post-content">
	<?php echo $this->Blogmill->field($post, 'content'); ?>
	<p class="last-modified"><?php __('Last modified:') ?> <?php echo $this->Blogmill->field($post, 'modified'); ?></p>
</div>
