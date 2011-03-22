<h1><span><?php echo __('Settings'); ?></span></h1>
<div class="table">
    <div class="cell" style="padding-right:40px;">
        <h2 class="theme"><?php
            printf(__('Your current theme is %s', true), $activeTheme);
            echo $this->Html->link(__('Change Theme', true), array('controller' => 'settings', 'action' => 'change_theme'), array('class' => 'cta simple'));
        ?></h2>
        <div class="theme-description">
            <p><?php echo @$theme['description']; ?></p> 
            <strong><?php __('Author') ?>:</strong> <?php echo @$this->Html->link($theme['author'], $theme['author_url']) ?>
        </div>
        <?php if (isset($theme['menus']) && count($theme['menus'])): ?>
        <div class="theme-menus">
            <h3><?php __('This Theme has menus you can configure:') ?></h3>
            <ul>
        	<?php
		        foreach ($theme['menus'] as $key => $name):
        			if (is_array($name)) {
		        		$description = $name['description'];
				        $name = $name['name'];
        			}
        	?>
		        <li>
        			<?php echo $this->Html->link($name, array('controller' => 'settings', 'action' => 'menu', $key), array('class' => 'cta')); ?>
                    <span class="description"><?php echo @$description; ?></span>
        		</li>
        	<?php endforeach; ?>
    	    </ul>
        </div>
        <?php endif; ?>
    </div>
    <div class="cell sidebar">
        <h2><?php __('Other plugins') ?></h2>
        <p><?php
        	printf(
		    __('Change settings for %s', true),
    		$this->Html->link(__('some plugins', true), array('action' => 'plugins'))
	    );
        ?></p>
    </div>
</div>
