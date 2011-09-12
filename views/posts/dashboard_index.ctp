<div class="posts index">
	<h1><span><?php __('Posts');?></span></h1>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('type');?></th>
			<th><?php echo $this->Paginator->sort(__('Title', true), 'display');?></th>
			<th><?php __('User');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
            <th><?php echo $this->Paginator->sort('status');?></th>
			<th><?php echo $this->Paginator->sort('category_id');?></th>
			<th class="actions"><?php __('Actions');?></th>
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
		<td><strong><?php echo $post['Post']['display']; ?></strong></td>
		<td>
			<?php echo $this->Html->link($post['User']['username'], array('controller' => 'users', 'action' => 'view', $post['User']['id'])); ?>
		</td>
		<td><?php echo $this->Time->format('M-d-Y, h:m', $post['Post']['created']); ?></td>
		<td><?php echo $this->Time->format('M-d-Y, h:m', $post['Post']['modified']); ?></td>
        <td><?php echo $post['Post']['draft'] ? __('Draft', true) : __('Published', true); ?></td>
		<td>
			<?php echo $this->Html->link($post['Category']['title'], array('controller' => 'categories', 'action' => 'view', $post['Category']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Blogmill->postLink($post, array('display' => __('View', true))); ?>
			<?php echo $this->Blogmill->postEditLink($post, __('Edit', true)); ?>
			<?php echo $this->Blogmill->postDeleteLink($post); ?>
			<?php echo $this->Html->link(__('Add to Menu', true), array('controller' => 'settings', 'action' => 'add_to_menu', 'post' => $post['Post']['id'])); ?>
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
