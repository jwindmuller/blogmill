<?php
class ContactsController extends AppController {

    var $components = array('Email');
    var $uses = array('Contact', 'Setting');

    public function send() {
        if ($this->Contact->save($this->data)) {
            $this->Email->to = $this->Setting->get('BlogmillDefault.blogmill_contact_email'); 
            $this->Email->subject = $this->data['Contact']['subject'];
            $this->Email->replyTo = $this->data['Contact']['email'];
            $name  = $this->data['Contact']['name'];
            $email = $this->data['Contact']['email'];
            $this->Email->from = sprintf('%s <%s>', $name, $email);
            if ($this->Email->send($this->data['Contact']['message'])) {
                $this->Session->setFlash(__('Thanks for contacting us, we\'ll be replying to you by mail soon', true), 'success');
                $this->redirect(array('controller' => 'contacts', 'action' => 'send'));
            } else {
                $this->Session->setFlash(__('There was a problem sending this email, please try again', true), 'error');
            }
        }
    }
}
