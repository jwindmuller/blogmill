<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
 		<legend><?php printf(__('Add %s', true), __('User', true)); ?></legend>
	<?php
		echo $this->Form->input('username');
		echo $this->Form->input('alias');
		echo $this->Form->input('password');
		echo $this->Form->input('email');
		echo $this->Form->input('blog_count');
		echo $this->Form->input('admin');
		echo $this->Form->input('profile');
		echo $this->Form->input('url');
		echo $this->Form->input('confirmation');
		echo $this->Form->input('question');
		echo $this->Form->input('answer');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Users', true)), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Posts', true)), array('controller' => 'posts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Post', true)), array('controller' => 'posts', 'action' => 'add')); ?> </li>
	</ul>
</div>