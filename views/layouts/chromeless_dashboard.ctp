<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo $html->charset(); ?> 
    <?php echo $html->meta(null, null, array('name' => 'robots', 'content' => 'noindex,nofollow')) ?> 
    <title>
        <?php __('Dashboard'); ?>
        <?php echo $title_for_layout; ?>
        - <?php __('Blogmill'); ?>
    </title>
    <?php echo $this->Javascript->link('jquery'); ?>
    <!-- <script language="javascript" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script> -->
    <?php echo $javascript->link('fake_dropdown'); ?> 
    <?php echo $javascript->link('dashboard'); ?> 
    <?php echo $javascript->link('tiny_mce/tiny_mce'); ?> 
    <?php echo $javascript->link('tiny_mce/jquery.tinymce'); ?> 
    <?php echo $html->css('dashboard'); ?>
    <?php echo $html->css('jquery_ui_theme/style'); ?>
    <?php echo $this->Javascript->link('jquery.ui/jquery.ui.core'); ?>
    <?php echo $this->Javascript->link('jquery.ui/jquery.ui.widget'); ?>
    <?php echo $this->Javascript->link('jquery.ui/jquery.ui.mouse'); ?>
    <?php echo $this->Javascript->link('jquery.ui/jquery.ui.draggable'); ?>
    <?php echo $this->Javascript->link('jquery.ui/jquery.ui.droppable'); ?>
    <?php echo $this->Javascript->link('jquery.ui/jquery.ui.sortable'); ?>
    <?php echo $this->Javascript->link('jquery.ui/jquery.ui.position'); ?>
    <?php echo $this->Javascript->link('jquery.ui/jquery.ui.dialog'); ?>
    <?php echo $this->Javascript->link('jquery.ui/jquery.effects.core'); ?>
    <?php echo $this->Javascript->link('jquery.ui/jquery.effects.blind'); ?>
    <?php echo $scripts_for_layout; ?>
<body class="chromeless">
    <?php echo $this->Session->flash(); ?>
    <?php echo $content_for_layout; ?>
</body>
</html>