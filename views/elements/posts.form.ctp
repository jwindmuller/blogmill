<div class="posts form">
<?php
	$post_id = isset($this->params['pass'][2]) ? $this->params['pass'][2] : '';
	$url = str_replace($this->base, '', $this->here) . '/' . $post_id;
?>
<?php echo $this->Form->create('Post', compact('url') + array('type' => 'file'));?>
<?php echo $this->Form->input('id'); ?>
	<fieldset>
 		<legend><?php
			$title = __('New <em>%s</em>', true);
			if (isset($post_id)) {
				$title = __('Edit <em>%s</em>', true);
			}
			printf($title, Inflector::humanize(Inflector::underscore($type)));
		?></legend>
	<?php
		echo $this->BlogmillForm->inputs();
		echo $this->Form->input('guide', array('type' => 'hidden'));
	?>
	</fieldset>
	
<?php echo $this->Form->end(__('Submit', true));?>
</div>
