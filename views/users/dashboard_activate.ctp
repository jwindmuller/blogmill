<h1><span><?php printf(__('Welcome %s', true), $username); ?></span></h1>
<p><?php __('Please setup your password and security question'); ?></p>
<?php echo $this->Form->create('User', array('url' => array('controller'=> 'users', 'action' => 'activate', $confirmation))); ?>
    <fieldset>
    <div class="form-wrap">
	    <div class="group-wrap">
            <div class="group">
            <strong class="title"><?php __('Your new password') ?></strong>
            <?php
                echo $this->Form->input('username', array('type' => 'hidden', 'value' => $username));
                echo $this->Form->input('password');
                echo $this->Form->input('password_confirm', array('label' => __('Confirm Password', true), 'type' => 'password'));
            ?>
            </div>
        </div>
	    <div class="group-wrap">
            <div class="group">
            <strong class="title"><?php __('Security question') ?></strong>
            <?php
                echo $this->Form->input('question');
                echo $this->Form->input('answer');
            ?>
            </div>
        </div>
    </div>
    <fieldset>
<?php echo $this->Form->end(__('Go!', true)); ?>

