<h1><span><?php __('Login'); ?></span></h1>
<?php echo $this->Form->create('User'); ?>
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
<?php echo $this->Form->end(__('Login', true)); ?>
