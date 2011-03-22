<h1><span><?php __('Setup the first user'); ?></span></h1>
<p><?php __('This will be the first user, like God...') ?></p>
<?php echo $this->Form->create('User', array('url' => array('controller'=>'setup'))); ?>
    <fieldset>
    <div class="form-wrap">
	    <div class="group-wrap">
            <div class="group">
            <?php
                echo $this->Form->input('username');
                echo $this->Form->input('password');
            ?>
            </div>
        </div>
    </div>
    <fieldset>
<?php echo $this->Form->end(__('Go!', true)); ?>
