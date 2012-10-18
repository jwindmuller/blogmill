<?php
class AttachmentsController extends AppController {

    public $name = 'Attachments';
    public $helpers = array('Attachment');

    public function chromeless_dashboard_management() {
        $namedParams = $this->params['named'];
        $guide = $namedParams['guide'];
        if ($this->RequestHandler->isPost() && empty($this->data)) {
            $this->Session->setFlash(__('The file you tried to upload may be to big, try a smaller size.', true));
            $this->redirect('');
        }
        if (!empty($this->data)) {
            $plugin = $namedParams['p'];
            $type = $namedParams['t'];
            $this->Attachment->setup($plugin, $type, $guide);
            if ($this->Attachment->save($this->data)) {
                $this->redirect(compact('guide'));
            }
        }
        $attachments = $this->Attachment->findAllByPostId($guide);
        foreach ($attachments as $i => $attachment) {
            extract($attachment['Attachment']);
            $this->helpers[] = $plugin . '.' . Inflector::camelize($type) . 'Upload';
        }
        $this->set(compact('attachments'));
    }
}