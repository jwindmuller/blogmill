<?php 
    printf(__('You have been registered in %s, welcome!', true), $blogmill_site_name);
?> 

<?php
    __('You need to complete you registration, to do so head to:');
?> 
<?php
    echo $this->Text->url(array('controller' => 'users', 'action' => 'activate', 'dashboard' => true, $confirmation), true);
?>


