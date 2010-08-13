<?php echo $this->Blogmill->postEditLink($post, null, array('class' => 'book')); ?>
<h1>
	<?php
		$last_char = substr($post['Post']['author'], -1, 1);
		$pattern = __(':author\'s', true);
		if ($last_char == 's') {
			$pattern = __(':author\'', true);
		}
	?>
	<span><?php echo String::insert($pattern, array('author' => $post['Post']['author'])); ?></span>
	<?php echo $post['Post']['title']; ?>
</h1>
<div id="post-content" class="book">
	<div class="evaluation">
		<div class="points">
			<strong>My Evaluation of <?php echo $this->Blogmill->field($post, 'title'); ?></strong>
			<dl>
				<dt>Was it fun to read?</dt>
				<dd>
					<?php $evaluation = $this->Blogmill->field($post, 'evaluation_fun'); ?>
					<div class="selected-<?php echo $evaluation; ?>"></div>
					<strong class="value"><?php echo $evaluation ?>/5</strong>
				</dd>
				<dt>Kept me interested?</dt>
				<dd>
					<?php $evaluation = $this->Blogmill->field($post, 'evaluation_interesting'); ?>
					<div class="selected-<?php echo $evaluation; ?>"></div>
					<strong class="value"><?php echo $evaluation ?>/5</strong>
				</dd>
				<dt>What about the characters?</dt>
				<dd>
					<?php $evaluation = $this->Blogmill->field($post, 'evaluation_characters'); ?>
					<div class="selected-<?php echo $evaluation; ?>"></div>
					<strong class="value"><?php echo $evaluation ?>/5</strong>
				</dd>
				<dt>It had a good ending?</dt>
				<dd>
					<?php $evaluation = $this->Blogmill->field($post, 'evaluation_ending'); ?>
					<div class="selected-<?php echo $evaluation; ?>"></div>
					<strong class="value"><?php echo $evaluation ?>/5</strong>
				</dd>
				<dt>Overall Rating</dt>
				<dd>
					<?php $evaluation = $this->Blogmill->field($post, 'evaluation_overall'); ?>
					<div class="selected-<?php echo $evaluation; ?>"></div>
					<strong class="value"><?php echo $evaluation ?>/5</strong>
				</dd>
			</dl>
		</div>
		<?php echo $this->Blogmill->image($post, 'book_cover', array('class' => 'showcase-image')); ?>
	</div>
	<?php echo $post['Post']['content']; ?>
</div>
