<div class="comments index">
	<h1><span><?php __('Comments');?></span></h1>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('post_id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('email');?></th>
			<th><?php echo $this->Paginator->sort('url');?></th>
			<th><?php echo $this->Paginator->sort('content');?></th>
			<th><?php echo $this->Paginator->sort(__('When', true), 'created');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($comments as $comment):
        $isSpam = $comment['Comment']['spam'];
		$class = ' class="';
		if ($i++ % 2 == 0) {
			$class .= 'altrow ';
		}
        $class .= $isSpam ? 'spam' : '';
        $class .= '"';
	?>
	<tr<?php echo $class;?>>
		<td>
			<?php
				echo $this->Blogmill->postLink($comment['Post']);
			?>
		</td>
		<td><?php echo $comment['Comment']['name']; ?>&nbsp;</td>
		<td><?php echo $comment['Comment']['email']; ?>&nbsp;</td>
		<td><?php echo $comment['Comment']['url']; ?>&nbsp;</td>
		<td><?php echo $comment['Comment']['content']; ?>&nbsp;</td>
		<td><?php echo $this->Time->niceShort($comment['Comment']['created']); ?>&nbsp;</td>
        <td class="actions">
            <?php echo $this->Html->link(
                __('View', true),
                array('action' => 'view', $comment['Comment']['id'])
            ); ?>
            <?php echo $this->Html->link(
                __('Edit', true),
                array('action' => 'edit', $comment['Comment']['id'])
            ); ?>
            <?php
            $approved = $comment['Comment']['approved'];
            echo $this->Html->link(
                $approved ? __('Disapprove', true) : __('Approve', true),
                array('action' => 'approve', $comment['Comment']['id'], ($approved ? 'no' : 'yes'))
            ); ?>
            <?php
            $prompt = sprintf(
                __('Are you sure you want to mark as spam and delete this comment from "%s"?', true),
                trim($comment['Comment']['name'])
            );
            if ($isSpam) {
                $prompt = false;
            }
            echo $this->Html->link(
                $isSpam ? __('Not Spam', true) : __('Spam', true),
                array('action' => 'spam', $comment['Comment']['id'], ($isSpam ? 'no' : 'yes')), null,
                $prompt
            ); ?>
            <?php echo $this->Html->link(
                __('Delete', true),
                array('action' => 'delete', $comment['Comment']['id']), null,
                sprintf(
                    __('Are you sure you want to delete this comment from "%s"?', true),
                    trim($comment['Comment']['name'])
                )
            ); ?>
        </td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
	    <?php echo $this->Blogmill->actionsList($actions); ?>
	</ul>
</div>
