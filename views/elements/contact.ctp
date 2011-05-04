<div id="contact">
    <h1><?php __('Contact'); ?></h1>
    <?php
        $flash = $this->Session->read('Message.flash');
        $this->Session->delete('Message.flash');
        if ($flash) :
    ?>
        <h2 class="<?php echo $flash['element']; ?>"><?php echo $flash['message']; ?></h2>
    <?php
        endif;
    ?>
    <?php echo $this->BlogmillForm->contactForm(); ?>
</div>
