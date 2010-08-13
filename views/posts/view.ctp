<?php 
	list($plugin, $type) = explode('.', $post['Post']['type']);
	$plugin = Inflector::underscore($plugin);
	$type = Inflector::underscore($type);
?> 
<div class="posts view">
	<?php echo $this->element($type . '/view', compact('plugin')); ?> 
</div>
<div id="comments">
	<p><strong class="title"><?php __('Comments'); ?></strong></p>
	<?php if (count($post['Comment']) == 0): ?>
		<p><?php __('No comments yet, leave yours!'); ?></p>
	<?php endif ?>
	<?php foreach ($post['Comment'] as $i => $comment): ?>
		<div class="comment">
		<?php
			$name = $comment['name'];
			if (!empty($comment['url'])) {
				$name = $html->link($name, $comment['url'], array('rel' => 'nofollow'));
			}
		?>
			<div class="content">
			<?php echo $name; ?> <?php __('said:') ?>
			<p><?php echo $comment['content'] ?></p>
			</div>
		</div>
	<?php endforeach ?>
	<div class="form">
	<?php echo $this->Form->create('Comment', array('url' => $this->Blogmill->postURL($post, array('#' => 'comments')), 'class' => 'rounded-10'));?>
		<fieldset>
	 		<legend><?php printf(__('Your turn!', true)); ?></legend>
		<?php
			echo $this->Form->input('name');
			echo $this->Form->input('email');
			echo $this->Form->input('url');
			echo $this->Form->input('content', array('label' => __('Comment', true)));
		?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
	</div>
</div>