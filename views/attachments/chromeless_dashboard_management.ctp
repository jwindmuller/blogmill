<?php 
    $urlBase = array('controller' => 'attachments', 'action' => 'management', 'guide' => $this->params['named']['guide']);
?>
    <ul class="attachments menu">
        <li><?php
            echo $this->Html->link(
                $this->Html->image('dashboard/attachment.png', array('alt' => __('Current Attachments', true))),
                $urlBase,
                array('escape' => false)
            );
        ?></li>
        <?php foreach ($attachmentDefinitions as $plugin => $definition): ?>
            <?php foreach ($definition as $name => $d): ?>
            <li><?php
                echo $this->Html->link(
                    $this->Html->image('/' . Inflector::underscore($plugin) . '/attachment-' . $d['type'] . '.png', array('alt' => $d['name'])),
                    $urlBase + array('p' => $plugin, 't' => $d['type']),
                    array('escape' => false)
                );
            ?></li>
            <?php endforeach ?>
        <?php endforeach ?>
    </ul>

<?php
    if (isset($this->params['named']['p']) && isset($this->params['named']['t'])):
        $plugin = $this->params['named']['p'];
        $type = $this->params['named']['t'];
        $typeToDisplay = $attachmentDefinitions[$plugin][$type];

        echo $this->Attachment->form($type, $typeToDisplay);
    else:
?>
<ul class="attachment-list">
<?php
        foreach ($attachments as $i => $attachment):
            extract($attachment['Attachment']);
            $helper = Inflector::camelize($type) . 'Upload';
?>
            <li>
                <p>
                    <?php echo $this->{$helper}->preview($attachment); ?>
                </p>
                <p>
                    <?php __('To insert this attachment, paste this code:'); ?>
                    <span class="code">%att-<?php echo $id; ?></span>
                </p>
            </li>
<?php
        endforeach;
?>
</ul>
<?php
    endif;
?>