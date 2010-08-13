<?php
App::import('Core', 'Object');
class BlogmillUnmatchedRoute extends CakeRoute {
	
	public function match($url)
	{
		if(isset($url['type'])) {
			unset($url['type']);
			$url = parent::match($url);
			return $url;
		}
	}
}