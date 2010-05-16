<?php $type = $post['Post']['type']; ?>
<div class="posts view">
	<?php echo $this->element($type . '/view', array('plugin' => $type)); ?>
</div>