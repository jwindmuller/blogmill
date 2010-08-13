<div class="footer-element" id="blogroll">
	<strong class="title">Blogroll</strong>
	<ul>
		<?php foreach ($themeData['blogroll'] as $link): ?>
		<?php endforeach ?>
		<li class="rounded-10">
			<?php
				$title = $link['Post']['title'];
				$description = $link['Post']['description'];
				$url = $link['Post']['url'];
			?><a href="<?php echo $url; ?>"><?php echo $title ?> <span><?php echo $description; ?></span></a></li>
	</ul>
</div>