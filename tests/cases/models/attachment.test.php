<?php
/* Attachment Test cases generated on: 2012-10-05 22:42:22 : 1349476942*/
App::import('Model', 'Attachment');

class AttachmentTestCase extends CakeTestCase {
    var $fixtures = array('app.attachment', 'app.post', 'app.user', 'app.category', 'app.comment', 'app.field');

    function startTest() {
        $this->Attachment =& ClassRegistry::init('Attachment');
    }

    function endTest() {
        unset($this->Attachment);
        ClassRegistry::flush();
    }

}