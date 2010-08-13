<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php __('Default Layout'); ?>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('/book_reviews/css/styles');
		echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js');
		echo $this->Html->css('../js/jquery.ui.stars/jquery.ui.stars');
		echo $scripts_for_layout;
	?>
</head>
<body>
	<div id="top" class="center-wrap">
		<?php echo $this->element('layout/default.menu'); ?>
		<?php echo $this->element('layout/default.head'); ?>
	</div>
	<?php echo $content_for_layout; ?>
	<?php if (count($themeData['books'])>1): ?>
		<div id="latest-books">
			<div class="center-wrap">
				<?php echo $this->element('layout/default.more_reviews'); ?>
			</div>
		</div>
	<?php endif ?>
	<div id="footer">
		<div class="center-wrap">
			<?php echo $this->element('layout/default.footer.twitter'); ?>
			<?php echo $this->element('layout/default.footer.blogroll'); ?>
			<?php echo $this->element('layout/default.footer.community'); ?>
		</div>
	</div>
</body>
</html>