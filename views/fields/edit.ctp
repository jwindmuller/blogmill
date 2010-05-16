<div class="fields form">
<?php echo $this->Form->create('Field');?>
	<fieldset>
 		<legend><?php printf(__('Edit %s', true), __('Field', true)); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('post_id');
		echo $this->Form->input('name');
		echo $this->Form->input('value');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Field.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Field.id'))); ?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Fields', true)), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Posts', true)), array('controller' => 'posts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Post', true)), array('controller' => 'posts', 'action' => 'add')); ?> </li>
	</ul>
</div>