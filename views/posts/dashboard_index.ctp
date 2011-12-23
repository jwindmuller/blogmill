<div class="posts index">
	<h1><span><?php __('Posts');?></span></h1>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('type');?></th>
			<th><?php echo $this->Paginator->sort(__('Title', true), 'display');?></th>
			<th><?php __('Edit'); ?></th>
			<th><?php __('Author');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
            <th><?php echo $this->Paginator->sort(__('Draft', true), 'draft');?></th>
            <th><?php echo $this->Paginator->sort(__('Publish Date', true), 'published');?></th>
			<th><?php echo $this->Paginator->sort('category_id');?></th>
			<th class="actions"><?php __('Delete');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($posts as $post):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td>
			<?php
                $type = Set::extract($postTypes, $post['Post']['type'].'.name');
                if(!$type) {
                    $type = $post['Post']['type'];
                }
                echo $this->Html->link($type, array('controller' => 'posts', 'action' => 'index', $post['Post']['type']));
            ?>
		</td>
		<td>
			<strong><?php
				echo $this->Blogmill->postLink($post);
			?></strong>
		</td>
		<td><?php echo $this->Blogmill->postEditLink($post, __('Edit', true)); ?></td>
		<td>
			<?php echo $this->Html->link($post['User']['username'], array('controller' => 'users', 'action' => 'view', $post['User']['id'])); ?>
		</td>
		<td><?php echo $this->Time->format('M-d-Y, H:i', $post['Post']['modified']); ?></td>
		<td><?php echo $post['Post']['draft'] ?  __('Draft', true) : '&nbsp;'; ?></td>
        <td><?php
			if ($post['Post']['published'] !== null) 
				echo $this->Time->format('M-d-Y, H:i', $post['Post']['published']);
			else echo '&nbsp;'
		?></td>
		<td>
			<?php echo $this->Html->link($post['Category']['title'], array('controller' => 'categories', 'action' => 'view', $post['Category']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Blogmill->postDeleteLink($post); ?>
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
