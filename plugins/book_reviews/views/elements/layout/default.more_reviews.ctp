<h2><?php __('More <br /> Book Reviews'); ?></h2>
<?php
	$books = $themeData['books'];
	unset($books[0]);
?>
<ul>
	<?php foreach ($books as $i => $book): ?>
		<li class="<?php echo 'item-' . $i; ?>">
			<?php
				$home_small = 'home_small.' . $this->Blogmill->field($book, 'home_small');
				$postGuide = $this->Blogmill->guide($book);
				$home_small = $this->Blogmill->image($book, 'home_small', array('class' => 'rounded-10'));
				echo $this->Blogmill->postLink($book,
					array('display' => $home_small), array('escape' => false, 'class' => 'cover')
				);
			?>
			<div class="book-detail">
				<h3><?php echo $this->Blogmill->field($book, 'title'); ?> <sup>by <?php echo $this->Blogmill->field($book, 'author'); ?></sup></h3>
				<p><?php echo $this->Blogmill->excerpt($book, 200); ?></p>
				<?php
					echo $this->Blogmill->postLink($book,
						array('display' => __('Read the complete review', true)),
						array('class' => 'button')
					);
				?>
				<div class="current"></div>
			</div>
		</li>
	<?php endforeach; ?>
</ul>
<?php
	echo $this->Javascript->link('/BookReviews/js/jquery.more_reviews');
?>