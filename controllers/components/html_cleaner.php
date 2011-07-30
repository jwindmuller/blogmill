<?php
App::import('Core', 'Sanitize');
App::import('Vendor', 'HTMLCleaner', array('file' => 'HTMLCleaner.php'));
class HtmlCleanerComponent extends Object {
    
    var $controller;
    var $cleaner;
    
    function startup(&$controller) {
        $this->controller = &$controller;
    }

    function cleanup($content, $tagWhitelist = array()) {
        $this->cleaner = new HTMLCleaner();
        $this->cleaner->Options['UseTidy'] = true;
        $this->cleaner->Options['OutputXHTML'] = true;
        $this->cleaner->Options['Optimize'] = true;
        $this->cleaner->Options['DropEmptyParas'] = true;
        $content = preg_replace('/rel=[\'"]nofollow[\'"]/', '', $content);
        $this->cleaner->html = $content;
        if (is_array($tagWhitelist)) {
            $this->cleaner->Tag_whitelist = '<' . join('><', $tagWhitelist) . '>';
        }
        $this->cleaner->Attrib_blacklist = '[^= ]*';
        $content = $this->cleaner->cleanUp('utf8');
        $content = preg_replace('/<a(\s)?/', '<a rel="nofollow"\\1', $content);
        return $content;
    }
}
?>
