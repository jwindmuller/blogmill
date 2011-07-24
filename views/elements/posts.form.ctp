<div class="posts form">
    <h1><span><?php echo $postTypes[$plugin][$type]['name']; ?></span></h1>

<?php
	$post_id = isset($this->params['pass'][2]) ? $this->params['pass'][2] : '';
	$url = str_replace($this->base, '', $this->here) . '/' . $post_id;
?>
<?php echo $this->Form->create('Post', compact('url') + array('type' => 'file'));?>
<?php echo $this->Form->input('id'); ?>
	<fieldset>
		<div class="form-wrapper">
			<div class="cell form-main">
				<?php echo $this->BlogmillForm->inputs('form-main'); ?>
			</div>
			<div class="cell form-sidebar">
				<?php echo $this->BlogmillForm->inputs('form-sidebar'); ?>			
			</div>
		</div>
	<?php echo $this->Form->input('guide', array('type' => 'hidden')); ?>
	</fieldset>
<?php
    echo $this->Form->end(sprintf(
            empty($post_id) ? __('Create %s', true) : __('Update %s', true),
            Inflector::humanize(Inflector::underscore($type))
    ));
?>
</div>
