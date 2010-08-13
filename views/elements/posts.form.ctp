<div class="posts form">
<?php
	$post_id = isset($this->params['pass'][1]) ? $this->params['pass'][1] : '';
	$url = str_replace($this->base, '', $this->here) . '/' . $post_id;
?>
<?php echo $this->Form->create('Post', compact('url') + array('type' => 'file'));?>
<?php echo $this->Form->input('id'); ?>
	<fieldset>
 		<legend><?php printf(__('New <em>%s</em>', true), Inflector::humanize(Inflector::underscore($type))); ?></legend>
	<?php
		echo $this->BlogmillForm->inputs();
		echo $this->Form->input('guide', array('type' => 'hidden'));
	?>
	</fieldset>
	
<?php echo $this->Form->end(__('Submit', true));?>
</div>