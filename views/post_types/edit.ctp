<div class="postTypes form">
<?php echo $this->Form->create('PostType');?>
	<fieldset>
 		<legend><?php printf(__('Edit %s', true), __('Post Type', true)); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('original');
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('PostType.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('PostType.id'))); ?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Post Types', true)), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Posts', true)), array('controller' => 'posts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Post', true)), array('controller' => 'posts', 'action' => 'add')); ?> </li>
	</ul>
</div>