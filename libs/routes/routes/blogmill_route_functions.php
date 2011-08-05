<?php
if (!function_exists('array_permutations')) {
    function array_permutations($items, $perms = array()) {
        static $permuted_array;
        if (empty($items)) {
            $permuted_array[] = $perms;
        } else {
            for ($i = count($items) - 1; $i >= 0; --$i) {
                $newitems = $items;
                $newperms = $perms;
                list($foo) = array_splice($newitems, $i, 1);
                array_unshift($newperms, $foo);
                array_permutations($newitems, $newperms);
            }
            return $permuted_array;
        }
    }
}
class BlogmillRouteFunctions {
    
    private $postIndexes = array();
    
    function &getInstance() {
		static $instance = array();

		if (!$instance) {
			$instance[0] =& new BlogmillRouteFunctions();
		}
		return $instance[0];
	}
    
    public function postIndex($url, $types, $title) {
        $self = BlogmillRouteFunctions::getInstance();
        $perms = array_permutations($types);
        foreach( $perms as $i => $perm ) {
            $type = join(',', $perm);
            $self->postIndexes[$type] = $title;
            Router::connect($url, array('controller' => 'posts', 'action' => 'index', $type));
        }
    }
    
    public function getIndexName($type) {
        $self = BlogmillRouteFunctions::getInstance();
        if ( isset($self->postIndexes[$type]) ) {
            return $self->postIndexes[$type];
        }
        return false;
    }
}