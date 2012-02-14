<h1><span><?php __('Login'); ?></span></h1>
<?php echo $this->Form->create('User'); ?>
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
    <?php
        $after = ' ' . $this->Html->link(
            __('lost password', true),
            array('controller' => 'users', 'action' => 'recover'),
            array('rel' => 'nofollow')
        );
        echo $this->Form->submit(__('Login', true), compact('after')); 
    ?>
<?php echo $this->Form->end(); ?>

