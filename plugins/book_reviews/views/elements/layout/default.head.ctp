
	<div id="showcase" class="rounded-10">
		<div class="wrap">
			<div class="promo">
				<h1 class="logo"><?php
					echo $this->Html->image(
						'/book_reviews/img/home/logo.png',
						array('alt' => 'Mary Windmuller', 'width' => '262', 'height' => '78')
					);
				?> <span>Book reviews by yours truly!</span></h1>
				<p>Hi, my name is Mary. I like to read books and wanted to share what I think about them. That’s the reason for this site to <?php echo $this->Html->link('publish reviews of the books I read', array('controller' => 'posts', 'action' => 'index', 'type' => 'BookReviews')); ?>.</p>
				<p>Aside from that,  you will find other random thoughts, ideas and experiences in the <?php echo $this->Html->link('notepad', array('controller' => 'posts', 'action' => 'index', 'type' => 'Journal')); ?>.</p>
			</div>
			<div class="latest">
				<ul>
					<li class="latest-review">
						<?php
						   $latestBook = $themeData['books'][0];
						?>
						<p class="side">
							<?php
								$title = sprintf(
									'<strong class="title">%s</strong> %s',
									$latestBook['Post']['display'], $this->Blogmill->excerpt($latestBook, 120)
								);
								echo $this->Blogmill->postLink($latestBook, array('display' => $title), array('escape' => false));
							?>
						</p>
						<div class="showcase_image active">
							<?php
								echo $this->Blogmill->image($latestBook, 'home_showcase');
							?>
							<div class="highlight">
								<div class="current-selection"></div>
								<div class="caption">
									<?php echo $this->Blogmill->postLink($latestBook, null, array('class' => 'title')); ?>
									<?php echo $this->Blogmill->postLink(
										$latestBook,
										array(
											'display' => sprintf(__('by %s', true), $latestBook['Post']['author'])
										),
										array('class' => 'author')
									); ?>
									<?php
										$evaluationOverall = $this->Blogmill->field($latestBook, 'evaluation_overall');
									?>
									<div id="evaluation">
										<strong>Overall Rating</strong>
										<div id="gauge">
											<div class="selected-<?php echo $evaluationOverall; ?>">(<?php echo $evaluationOverall ?>/5)</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</li>
					<li class="other-stuff">
						<?php if (isset($themeData['latest_post'][0])): ?>
							<?php
							   $latestJournalEntry = $themeData['latest_post'][0];
							?>
							<p class="side">
								<?php
									$content = '<strong class="title">Other Stuff I liked</strong><strong>%s</strong><br />%s';
									$content = sprintf($content,
										$this->Blogmill->field($latestJournalEntry, 'title'),
										$this->Blogmill->excerpt($latestJournalEntry, 100)
									);
									echo $this->Blogmill->postLink($latestJournalEntry, array('display' => $content), array('escape' => false));
								?>
							</p>
							<div class="showcase_image">
								<?php
									echo $this->Blogmill->image($latestJournalEntry, 'home_showcase');
								?>
								<div class="highlight">
									<div class="current-selection"></div>
									<div class="caption">
										<?php echo $this->Blogmill->postLink($latestJournalEntry, null, array('class' => 'title')); ?>
										<?php echo $this->Blogmill->postLink(
											$latestJournalEntry,
											array('display' => $this->Blogmill->excerpt($latestJournalEntry)),
											array('class' => 'author')
										); ?>
									</div>
								</div>
							</div>
						<?php endif ?>
					</li>
					<li class="contact">
						<p class="side"><a href="#">
							<strong class="title">Get in touch!</strong>
							Tell me what can I do better! Let me know I made I mistake! Say hi!
							</a>
						</p>
						<div class="showcase_image">
							<div class="highlight">
								<div class="current-selection"></div>
								<div class="caption">
									<?php
										echo $this->Form->create('Contact');
										echo $this->Form->input('name', array('label' => __('Your name', true)));
										echo $this->Form->input('email', array('label' => __('Your email', true)));
										echo $this->Form->input('comment', array('label' => __('What\'s on your mind?', true), 'type' => 'textarea'));
										echo $this->Form->end(__('Send your comment', true));
									?>
								</div>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
<?php
	echo $this->Javascript->link('/BookReviews/js/jquery.home_showcase');
?>