<?php
if (Configure::read('debug') < 2) {
	return false;
}
    $email_debug = $this->Session->read('Message.email');
if (!$email_debug) {
    return false;
}
    $this->Session->delete('Message.email');
?>
<table border="0" cellpadding="0">
<tr><th><?php __('Email');?></th></tr>
<tr><td><?php echo $email_debug['message']; ?></td></tr>
</table>
