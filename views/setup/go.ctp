<h1><?php echo __('Setup the first user'); ?></h1>
<p><?php __('This will be the first user, like God...') ?></p>
<?php echo $this->Form->create('User', array('url' => array('controller'=>'setup'))); ?>
<?php echo $this->Form->input('username'); ?>
<?php echo $this->Form->input('password'); ?>
<?php echo $this->Form->end(__('Go!', true)); ?>