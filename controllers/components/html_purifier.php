<?php 
App::import('Vendor','HTMLPurifier' ,array('file'=>'htmlpurifier'.DS.'library'.DS.'HTMLPurifier.auto.php')); 
class HtmlPurifierComponent extends Object {
	var $controller;
	var $purifier;

	function startup( &$controller ) {
		//the next few lines allow the config settings to be cached
		$config = HTMLPurifier_Config::createDefault();
		$config->set('HTML.DefinitionID', 'Blogmill');
		$config->set('HTML.DefinitionRev', 1);
		//levels describe how aggressive the Tidy module should be when cleaning up html
		//four levels: none, light, medium, heavy
		$config->set('HTML.TidyLevel', 'heavy');
		//check the top of your html file for the next two
		$config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
		$config->set('HTML.AllowedAttributes', array('a.href', 'a.title','img.src', '*.class'));
		$config->set('HTML.AllowedElements', array('a','img', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'blockquote', 'ul', 'ol', 'li'));
		$this->purifier = & new HTMLPurifier($config);
	}

	function __call($function, $args) {
		return call_user_func_array(array($this->purifier, $function), $args);
	}
}
?>