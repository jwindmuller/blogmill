<h1><?php 
    printf(__('You have been registered in %s, welcome!', true), $blogmill_site_name);
?></h1>
<p>
<?php
    __('You need to complete you registration, to do so head to:');
?>
</p>
<p>
<?php
    echo $this->Html->link($this->Text->url(array('controller' => 'users', 'action' => 'activate', 'dashboard' => true, $confirmation), true));
?>



