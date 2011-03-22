<h1><span><?php __('Change Theme'); ?></span></h1>
<?php foreach ($themes as $theme): ?>
	<h2 class="theme">
		<?php echo $theme['name']; ?> <?php echo $theme['version']; ?> 
		<?php echo $this->Html->link(__('Activate', true), array('theme' => $theme['id']), array('class' => 'cta simple')) ;?>
	</h2>
    <div class="theme-description">
    	<strong><?php __('Author') ?>:</strong> <?php echo @$this->Html->link($theme['author'], $theme['author_url']) ?>
	    <p><?php echo @$theme['description']; ?></p>
    </div>
    <br />
<?php endforeach ?>
