<h1 class="logo"><?php
	$img = $this->Html->image(
		'/book_reviews/img/inner/logo.png',
		array('alt' => 'Mary Windmuller', 'width' => '262', 'height' => '78')
	);
	echo $this->Html->link($img . ' <span>Book reviews by yours truly!</span>', '/', array('escape' => false));
?></h1>