<div class="categories form">
<?php echo $this->Form->create('Category');?>
<?php echo $this->Form->input('id'); ?>
	<fieldset>
 		<legend><?php printf(__('Add %s', true), __('Category', true)); ?></legend>
        <div class="form-wrapper">
        	<div class="cell form-main">
                <div class="group-wrap" style="width:100%;">
                    <div class="group">
                        <?php echo $this->Form->input('title'); ?>
                        <?php echo $this->Form->input('category_id', array('label' => __('Parent Category', true), 'empty' =>  true)); ?>
                        <span class="clear">&nbsp;</span>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
