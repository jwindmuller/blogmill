<?php
/* Attachments Test cases generated on: 2012-10-06 03:41:05 : 1349494865*/
App::import('Controller', 'Attachments');

class TestAttachmentsController extends AttachmentsController {
    var $autoRender = false;

    function redirect($url, $status = null, $exit = true) {
        $this->redirectUrl = $url;
    }
}

class AttachmentsControllerTestCase extends CakeTestCase {
    var $fixtures = array('app.attachment', 'app.post', 'app.user', 'app.category', 'app.comment', 'app.field');

    function startTest() {
        $this->Attachments =& new TestAttachmentsController();
        $this->Attachments->constructClasses();
    }

    function endTest() {
        unset($this->Attachments);
        ClassRegistry::flush();
    }

}
