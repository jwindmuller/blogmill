<h1><span><?php __('Plugin Settings'); ?></span></h1>
<?php if (!isset($plugin)): ?>
	<h2><?php __('These are the plugins with settings') ?></h2>
	<ul>
		<?php foreach ($configurable_plugins as $name): ?>
			<li><?php echo $this->Html->link($name, array('action' => 'plugins', $name)); ?></li>
		<?php endforeach ?>
	</ul>
<?php else: ?>
	<h2><?php printf(__('Configuring %s', true), Inflector::humanize(Inflector::underscore($plugin))); ?></h2>
	<?php echo $this->Form->create('Setting', array('url' => array('controller' => 'settings', 'action' => 'plugins', $plugin))); ?>
	<?php
        foreach ($configurable_keys as $key => $settings):
            $label = sprintf('%s<p>%s</p>', $settings['label'], @$settings['longdesc']);
            $options = compact('label');
            if ( isset($settings['options']) ) {
                $options['type'] = 'select';
                $options['div'] = false;
                $options['options'] = $settings['options'];
            }
    ?>
        <fieldset class="group<?php echo isset($settings['multi']) ? ' multi' : ''; ?>">
        <?php if ( isset($settings['multi']) ): ?>
            <legend><?php echo $settings['label']; ?></legend>
            <p class="description"><?php echo $settings['longdesc']; ?></p>
                <?php
                    $width = count($settings['multi']);
                    $count = ${$key . '_count'};
                    for ($i=0; $i < $count; $i++) {
                        echo '<div class="multi-field">';
                        foreach ($settings['multi'] as $fieldName => $fieldLabel) {
                            echo $this->Form->input(
                                $key . '_' . $i . '_' . $fieldName,
                                array(
                                    'label' => $fieldLabel,
                                    'div' => array(
                                        'class' => 'input text multiple',
                                        'style' => 'width:' . (100/$width) . '%'
                                    )
                                )
                            );
                        }
                        if ($this->Form->isFieldError($key . '_' . $i)) {
                            echo $this->Form->error($key.'_'.$i, __('This Setting must be complete', true));
                        }
                        echo '<hr class="clear" /></div>';
                    }
                ?>
        <?php else: ?>
                <?php echo $this->Form->input($key, $options); ?>
        <?php endif ?>
        </fieldset>
	<?php endforeach ?>
	<?php echo $this->Form->end(__('Save', true)); ?>
<?php endif ?>
