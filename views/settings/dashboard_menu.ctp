<h1><span><?php __('Current Theme Settings'); ?></span></h1>
<h2><?php __('Theme'); ?>: <?php echo $theme['name']; ?></h2>
<h2 class="theme">
	<?php printf(__('Configuring "%s"', true), $menu_title) ?>
	<?php if ($menu_description): ?>
		<em class="description">(<?php echo $menu_description; ?>)</em>
	<?php endif ?>
</h2>

<?php $i=0; if (!$menu): ?>
	<p><?php __('No items in this menu'); ?></p><br />
<?php else: ?>
	<p><?php __('Items on this menu'); ?>:</p>
	<ul id="menu-items">
	<?php foreach ($menu as $i => $item): ?>
		<li class="menu-item<?php echo ($edit !== false && $edit == $i) ? ' edit' : ''; ?>">
			<?php if ($edit !== false && $edit == $i): ?>
                <?php echo $this->element('settings.menu_item.form', compact('i', 'menu_name', 'item')); ?>
			<?php else: ?>
				<strong class="title"><?php echo $item['title']; ?></strong>
			<?php endif ?>
			<div class="actions">
				<span class="move">
					<?php
						if ($i > 0)
						echo $this->Html->link(
							$this->Html->image('dashboard/move_up.png'),
							array('action' => 'menu_move_item', $menu_name, 'up', $i),
							array('escape' => false, 'title' => __('Move Up', true), 'alt' => __('Move Up', true))
						);
					?>
					<?php
						if ($i < count($menu) -1)
						echo $this->Html->link(
							$this->Html->image('dashboard/move_down.png'),
							array('action' => 'menu_move_item', $menu_name, 'down', $i),
							array('escape' => false, 'title' => __('Move Down', true), 'alt' => __('Move Down', true))
						);
					?>
				</span>
			<?php
				echo $this->Html->link(
					$this->Html->image('dashboard/view.png'), $item['url'],
					array('escape' => false, 'title' => __('View', true), 'alt' => __('View', true), 'class' => 'view')
				);
			?>
			<?php
				echo $this->Html->link(
					$this->Html->image('dashboard/edit.png'),
					array($menu_name, 'edit' => $i),
					array('escape' => false, 'title' => __('Edit', true), 'alt' => __('Edit', true), 'class' => 'edit')
				);
			?>
			<?php
				echo $this->Html->link(
					$this->Html->image('dashboard/delete.png'),
					array('controller' => 'settings', 'action' =>  'remove_from_menu', $menu_name, $i),
					array('escape' => false, 'title' => __('Delete', true), 'alt' => __('Delete', true), 'class' => 'delete'),
                    __('Delete this item from the menu?', true)
				);
			?>
			</div>
		</li>
	<?php $i++; endforeach; ?>
	</ul>
<?php endif; ?>
<?php if ($edit === false): ?>
<div class="menu-item">
	<strong>Add item:</strong>
    <?php echo $this->element('settings.menu_item.form', array('i' => 'new')); ?>
</div>
<?php endif ?>
<?php
	echo $this->Javascript->link('dashboard/menu_setup', false);
	$menuChangeURL = $this->Html->url(array('controller' => 'settings', 'action' => 'edit_menu', 'ext' => 'json'));
	$postListURL = $this->Html->url(array('controller' => 'posts', 'action' => 'list', 'ext' => 'json'));
	$sortingURL = $this->Html->url(array('controller' => 'settings', 'action' => 'menu_reorder', $menu_name, 'ext' => 'json'));
    $customIndexURL = $this->Html->url(array(
        'controller' => 'settings',
        'action' => 'get_index_url',
        'ext' => 'json'
    ));


	$selectorPostTypes = array();
	foreach ($postTypes as $plugin => $types) {
		$selectorPostTypes[$plugin] = array();
		foreach ($types as $type => $def) {
            $name = $type;
            if (isset($def['name'])) {
                $name = $def['name'];
            }
			$selectorPostTypes[$plugin][] = compact("type","name");
		}
	}
    $selectorPostTypes['_FixedPages'][] = array('type' => '_FixedPages', 'name' => __('Other Pages', true));
    $selectorPostTypes['_IndexPages'][] = array('type' => '__IndexPages', 'name' => __('List of Posts', true));

	$selectorPostTypes = $this->Javascript->object($selectorPostTypes);
	$handle = $this->Html->image('dashboard/move.png', array('class' => 'handle'));
	$js = <<<eojs
		$(function() {
			var menuList = $("#menu-items");
			var pageSelectorOptions = {
				'postListURL' :  '$postListURL',
                'sortingURL' : '$sortingURL',
                'customIndexURL' : '$customIndexURL',
				'postTypes' : $selectorPostTypes,
				'handle' : '$handle',
				'UrlInputClass' : '.settings-url'
			};
			menuList.menuSetup(pageSelectorOptions);
            $('.menu-item label').show().addClass('displayed').siblings('input').each(function(){
               if ($(this).val() !== '') {
                    $(this).siblings('label').hide();
               }
               $(this).focus(function() {
                    $(this).siblings('label').hide();
               }).blur(function() {
                   if ($(this).val() === '') {
                        $(this).siblings('label').show();
                   }
               });
            });
		});
eojs;
	echo $this->Javascript->codeBlock($js);
?>
