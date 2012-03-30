<?php
class ContactsController extends AppController {

    var $components = array('Email');
    var $uses = array('Contact', 'Setting');

    public function send() {
        if (!empty($this->data)) {
            if (!isset($this->data['Contact']['extra']) || !empty($this->data['Contact']['extra'])) {
                $this->_blogmill404Error();
            }
            if ($this->Contact->save($this->data)) {
                $emails = $this->Setting->get('BlogmillDefault.blogmill_contact_email');
                $selectedEmail = 0;
                if (isset($this->data['Contact']['to'])) {
                    $selectedEmail = $this->data['Contact']['to'];
                }
                if ($selectedEmail < 0 || !isset($emails[$selectedEmail]) ) {
                    $selectedEmail = 0;
                }
                $this->Email->to = $emails[0]['email'];
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
}
