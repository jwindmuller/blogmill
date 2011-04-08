<h1><span><?php __('Recover Password'); ?></span></h1>
<?php echo $this->Form->create('User'); ?>
    <div class="form-wrap">
	    <div class="group-wrap">
            <div class="group">
            <?php
                echo $this->Form->input('username');
            ?>
            </div>
        </div>
    </div>
    <?php
        echo $this->Form->submit(__('Recover Password', true), compact('after')); 
    ?>
<?php echo $this->Form->end(); ?>

