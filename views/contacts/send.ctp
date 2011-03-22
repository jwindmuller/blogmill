<div id="contact">
    <div class="left">
        <h1>Contactanos</h1>
        <p>Si tienes preguntas, o comentarios por favor envíalos utilizando el siguiente formulario:</p>
    </div>
    <div class="right">
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
    <br class="clear" />
    <div class="left address">
        <h1>Ubicación</h1>
        <p>Estamos ubicados en:</p>
        <?php echo $this->element('layout/vemosa.direccion'); ?>
    </div>
    <div class="right">
        <iframe width="570" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?q=vemosa+caracas&amp;gl=ve&amp;hl=es&amp;ie=UTF8&amp;view=map&amp;cid=2569695721278224456&amp;hq=vemosa+caracas&amp;hnear=&amp;t=h&amp;ll=10.492622,-66.867578&amp;spn=0.007385,0.012231&amp;z=16&amp;iwloc=A&amp;output=embed"></iframe><br />
        <a href="http://maps.google.com/maps?q=vemosa+caracas&amp;gl=ve&amp;hl=es&amp;ie=UTF8&amp;view=map&amp;cid=2569695721278224456&amp;hq=vemosa+caracas&amp;hnear=&amp;t=h&amp;ll=10.492622,-66.867578&amp;spn=0.007385,0.012231&amp;z=16&amp;iwloc=A&amp;source=embed" style="color:#0000FF;text-align:left">Ver mapa más grande</a>
    </div>
</div>
