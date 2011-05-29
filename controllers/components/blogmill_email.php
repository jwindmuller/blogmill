<?php 
class BlogmillEmailComponent extends Object {
    private $Controller;
    public $components = array('Email');

	public function startup(&$controller) {
		$this->Controller = $controller;
    }

    public function sendNotification($options) {
        $from_name = $options['from']['name'];
        $from_email = $options['from']['email'];
        $this->Email->from = $this->Email->replyTo = sprintf('%s <%s>', $from_name, $from_email);
        if (isset($options['reply_to'])) {
            $this->Email->replyTo = $options['reply_to'];
        }
        $to_name = $options['to']['name'];
        $to_email = $options['to']['email'];
        $this->Email->to = sprintf('%s <%s>', $to_name, $to_email);

        $this->Email->subject = $options['subject'];


        if (isset($options['template'])) {
            $this->Email->template = $options['template'];;
        }

        $data = array();
        if (isset($options['data'])) {
            $data = $options['data'];
        }

        $this->Controller->set($data);

        $this->Email->sendAs = 'both';
        $this->Email->delivery = 'mail';
        if (Configure::read('debug') > 1) {
            $this->Email->from = htmlentities($this->Email->form);
            $this->Email->to = htmlentities($this->Email->to);
            $this->Email->replyTo = htmlentities($this->Email->replyTo);
            $this->Email->delivery = 'debug';
        }


        return $this->Email->send();
    }


}	
