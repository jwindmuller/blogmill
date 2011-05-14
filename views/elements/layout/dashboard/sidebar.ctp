<?php if ($this->Session->check('Auth.User')): ?>
<?php
	$selectItems = array();
	$title = $session->read('CurrentBlog.name');
	$description = $session->read('CurrentBlog.name');
	$selectTitle = __('New Post', true);
	if (isset($postTypes)) {
		foreach ($postTypes as $plugin => $types) {
			$pluginItems = array();
			foreach ($types as $type => $definition) {
				$pluginItems[] = $html->link(
					isset($definition['name']) ? $definition['name'] : $type,
					array(
						'controller'	=> 'posts',
						'action'		=> 'add',
						$plugin, $type
					)
				);
			}
			$selectItems[] = sprintf('<ul><li>%s</li></ul>',join('</li><li>', $pluginItems));
			$pluginLow = low($plugin);
		}
	}
?>
<strong class="title"><?php echo $title; ?></strong>
<p><?php echo $description; ?></p>
<div id="sidebarselect">
	<strong><?php echo $selectTitle; ?></strong>
	<ul><?php printf('<li>%s</li>', join('</li><li>', $selectItems)); ?></ul>
</div>
<?php endif ?>
