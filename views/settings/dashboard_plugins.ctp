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
                $options['options'] = $settings['options'];
            }
    ?>
        <div class="group">
		<?php echo $this->Form->input($key, $options); ?>
        </div>
	<?php endforeach ?>
	<?php echo $this->Form->end(__('Save', true)); ?>
<?php endif ?>
