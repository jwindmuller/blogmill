<h1><span><?php __('Current Theme Settings'); ?></span></h1>
<h2><?php __('Theme'); ?>: <?php echo $theme['name']; ?></h2>
<h2 class="theme">
	<?php printf(__('Configuring "%s"', true), $menu_title) ?>
	<?php if ($menu_description): ?>
		<em class="description">(<?php echo $menu_description; ?>)</em>
	<?php endif ?>
</h2>
<?php
	echo $this->Form->create('Settings', array('url' => $this->passedArgs));
?>
<?php $i=0; if (!$menu): ?>
	<p><?php __('No items in this menu'); ?></p><br />
<?php else: ?>
	<p><?php __('Items on this menu'); ?>:</p>
	<ul id="menu-items">
	<?php foreach ($menu as $item): ?>
		<li>
			<?php echo $this->Html->link($item['title'], $item['url']); ?>
			<?php
				echo $this->Html->link(
					__(' (remove)', true),
					array('controller' => 'settings', 'action' =>  'remove_from_menu', $menu_name, $i)
				);
			?>
			<?php echo $this->Form->input(".$i.title", array('value' => $item['title'], 'type' => 'hidden')); ?>
			<?php echo $this->Form->input(".$i.url", array('value' => $item['url'], 'type' => 'hidden')); ?>
		</li>
	<?php $i++; endforeach; ?>
	</ul>
<?php endif ?>
<h2 class="theme"><?php __('Add an item'); ?></h2>
<div class="form-wrapper" style="width:600px">
	<div class="cell">
		<?php echo $this->Form->input(".$i.title", array('type' => 'text', 'label' => __('Title', true))); ?>
	</div>
	<div class="cell">
		<?php echo $this->Form->input(".$i.url", array('type' => 'text', 'label' => __('Url', true))); ?>
	</div>
	<div class="cell">
		<?php echo $this->Form->end(__('Submit', true)); ?>
	</div>
</div>
<?php
	echo $this->Javascript->link('dashboard/settings_menu', false);
	echo $this->Javascript->link('dashboard/page_selector', false);
	$postListURL = $this->Html->url(array('controller' => 'posts', 'action' => 'list', 'ext' => 'json'));
	$selectorPostTypes = array();
	foreach ($postTypes as $plugin => $types) {
		$selectorPostTypes[$plugin] = array();
		foreach ($types as $type => $def) {
			$selectorPostTypes[$plugin][] = $type;
		}
	}
	$selectorPostTypes = $this->Javascript->object($selectorPostTypes);
	$js = <<<eojs
		$(function() {
			var menuList = $("#menu-items");
			var pageSelectorOptions = {
				'postListURL' :  '$postListURL',
				'postTypes' : $selectorPostTypes
			};
			menuList.menuSetup(pageSelectorOptions);
		});
eojs;
	echo $this->Javascript->codeBlock($js);
?>