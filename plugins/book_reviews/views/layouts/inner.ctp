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
		echo $this->Html->css('/book_reviews/css/inner');
		echo $scripts_for_layout;
	?>
</head>
<body>
	<div id="top" class="center-wrap">
		<?php echo $this->element('layout/inner.logo') ?>
		<?php echo $this->element('layout/default.menu'); ?>
	</div>
	<div id="main">
		<div class="center-wrap">
			<?php echo $content_for_layout; ?>
		</div>
	</div>
</body>
</html>