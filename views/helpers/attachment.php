<?php 
class AttachmentHelper extends AppHelper {

    public $helpers = array('Form');
    private $types = array(
        'image' => 'file',
        'text' => 'textarea'
    );

    public function form($attachmentType, $definition) {
        extract($definition);
        $type = $this->__fieldType($type);

        $out = $this->Form->create('Attachment', array('url' => str_replace(Router::url('/'), '/', $this->here), 'type' => 'file'));
        $out.= $this->Form->input($attachmentType, compact('type', 'label'));
        $out.= $this->Form->input('guide', array('type' => 'hidden', 'value' => $this->params['named']['guide']));
        $out.= $this->Form->end(__('Add attachment', true));
        return $out;
    }

    private function __fieldType($type) {
        $fieldType = 'textarea';
        if (isset($this->types[$type])) {
            $fieldType = $this->types[$type];
        }
        return $fieldType;
    }
}