<h1><span><?php __('Set your new Password'); ?></span></h1>
<?php echo $this->Form->create('User', array('url' => array($confirmation))); ?>
    <div class="form-wrap">
	    <div class="group-wrap">
            <div class="group">
            <strong class="title"><?php __('Please answer your security question'); ?></strong>
            <?php
                echo $this->Form->input('answer', array('label' => $question));
            ?>
            </div>
        </div>
    </div>
    <?php
        echo $this->Form->submit(__('Recover Password', true), compact('after')); 
    ?>
<?php echo $this->Form->end(); ?>

