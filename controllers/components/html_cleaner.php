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
        $this->__allowFootnotesHTML($config);
        $purifier = new HTMLPurifier($config);
        return $purifier->purify($content);
    }

    function __allowFootnotesHTML($config) {
        $config->set('Attr.EnableID', true);
        $config->set('HTML.DefinitionID', 'enduser-customize.html tutorial');
        $config->set('HTML.DefinitionRev', 1);
        if ($def = $config->maybeGetRawHTMLDefinition()) {
            $def->addAttribute('sup', 'rel', 'Enum#footnote');
            $def->addAttribute('a', 'rev', 'Enum#footnote');
        }
    }
}
