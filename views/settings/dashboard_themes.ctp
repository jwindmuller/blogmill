<h1><span><?php echo __('Current Theme'); ?></span></h1>
<h2 class="theme"><?php
	echo $theme['name'];
	echo $this->Html->link(__('Change Theme', true), array('controller' => 'settings', 'action' => 'change_theme'), array('class' => 'cta simple'));
?></h2>

<h3><?php __('Menus') ?></h3>
<p><?php __('This are the menus defined in the active theme'); ?></p>
<?php if (isset($theme['menus']) && count($theme['menus'])): ?>
	<ul>
	<?php
		foreach ($theme['menus'] as $key => $name):
			if (is_array($name)) {
				$description = $name['description'];
				$name = $name['name'];
			}
	?>
		<li>
			<?php echo $this->Html->link($name, array('controller' => 'settings', 'action' => 'menu', $key)); ?>
			<?php if (isset($description)): ?>
				&mdash;
				<?php echo $description; ?>
			<?php endif ?>
		</li>
	<?php endforeach ?>
	</ul>
<?php else: ?>
	<p><?php __('No menus defined in this theme'); ?></p>
<?php endif ?>