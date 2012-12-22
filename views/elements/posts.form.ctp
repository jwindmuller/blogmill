<div class="posts form">
    <h1><span><?php echo $postTypes[$plugin][$type]['name']; ?></span></h1>

<?php
	$post_id = isset($this->params['pass'][2]) ? $this->params['pass'][2] : '';
	$url = str_replace($this->base, '', $this->here) . '/' . $post_id;
?>
<?php echo $this->Form->create('Post', compact('url') + array('type' => 'file'));?>
	<fieldset>
		<div class="form-wrapper">
			<div class="cell form-main">
				<?php echo $this->BlogmillForm->inputs('form-main'); ?>
                <?php
                    $guide = String::uuid();
                    if (isset($this->data['Post']['guide'])) {
                        $guide = $this->data['Post']['guide'];
                    } else {
                        $this->data['Post']['guide'] = $guide;
                    }
                    echo $this->Form->input('guide', array('type' => 'hidden', 'value' => $guide));
                    echo $this->Form->input('id', array('type' => 'hidden', 'value' => $guide));
                    // echo $this->element('post.form.attachments')
                ?>
			</div>
			<div class="cell form-sidebar">
				<?php echo $this->BlogmillForm->inputs('form-sidebar'); ?>
			</div>
		</div>
	</fieldset>
    <fieldset class="submit">
    <?php
        echo $this->Form->end(sprintf(
            empty($post_id) ? __('Create %s', true) : __('Update %s', true),
            Inflector::humanize(Inflector::underscore($postTypes[$plugin][$type]['name']))
        ));
    ?>
    </fieldset>

</div>
