<?php 
    $attachmentManagementURL = $this->Html->url(array('controller' => 'attachments', 'action' => 'management', 'chromeless_dashboard' => true, 'guide' => $this->data['Post']['guide']));
?>
<div class="iframe group-wrap">
    <div class="group">
        <iframe src="<?php echo $attachmentManagementURL; ?>"></iframe>
    </div>
</div>