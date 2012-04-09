<?php
App::import(array(
    'type' => 'Vendor',
    'name' => 'HTMLPurifier',
    'file' => 'htmlpurifier' . DS . 'HTMLPurifier.standalone.php'
));
class HtmlCleanerComponent extends Object {
    
    var $controller;
    var $cleaner;
    
    function startup(&$controller) {
        $this->controller = &$controller;
    }

    function cleanup($content, $tagWhitelist = array()) {
        if (!is_string($content)) {
            return $content;
        }
        $config = HTMLPurifier_Config::createDefault();
        $htmlAllowed = '';
        foreach( $tagWhitelist as $tag => $attrs ) {
            if (!is_array($attrs)) {
                $tag = $attrs;
                $htmlAllowed .= $tag . ',';
                continue;
            }
            foreach( $attrs as $i => $attr ) {
                $htmlAllowed .= $tag . '[' . $attr . '],';
            }
        }
        $htmlAllowed = trim($htmlAllowed, ',');
        $config->set('HTML.Allowed', $htmlAllowed);
        $purifier = new HTMLPurifier($config);
        return $purifier->purify($content);
    }
}
