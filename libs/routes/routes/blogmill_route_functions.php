<?php
class BlogmillRouteFunctions {
    
    private $postIndexes = array();
    private $extraFields = array();
    
    function &getInstance() {
		static $instance = array();

		if (!$instance) {
			$instance[0] =& new BlogmillRouteFunctions();
		}
		return $instance[0];
	}
    /**
     * Allows to register an index url with multiple types.
     * It figures out all the permutations of the array of types to catch any possible order the types are written in the url array.
     **/
    public function postIndex($url, $types, $title, $extraFields = array()) {
        $self = BlogmillRouteFunctions::getInstance();
        $perms = array();
        $perms = BlogmillRouteFunctions::array_permutations($types);
        foreach( $perms as $i => $perm ) {
            $type = join(',', $perm);
            $self->postIndexes[$type] = $title;
            $self->extraFields[$type] = $extraFields;
            Router::connect($url, array('controller' => 'posts', 'action' => 'index', $type));
        }
    }
    
    public function getIndexName($type) {
        if (is_array($type)) {
            $type = join(',', $type);
        }
        $self = BlogmillRouteFunctions::getInstance();
        if ( isset($self->postIndexes[$type]) ) {
            return $self->postIndexes[$type];
        }
        return false;
    }

    public function specialField($type, $field) {
        if (is_array($type)) {
            $type = join(',', $type);
        }
        $self = BlogmillRouteFunctions::getInstance();
        if ( isset($self->extraFields[$type]) && isset($self->extraFields[$type][$field]) ) {
            return $self->extraFields[$type][$field];
        }
    }

    /**
     * Permutation Functions
     **/
    private function array_permutations($items) {
        $permutations = array();
        for ($i=0; $i < BlogmillRouteFunctions::factorial(count($items)); $i++) { 
            $permutations[] = BlogmillRouteFunctions::array_permutation($items, $i);
        }
        return $permutations;
    }
    private function array_permutation($arr, $nth = null) {
        if ($nth === null) {
            return BlogmillRouteFunctions::array_permutations($arr);
        }

        $result = array();
        $length = count($arr);

        while ($length--) {
            $f = BlogmillRouteFunctions::factorial($length);
            $p = floor($nth/$f);
            $result[] = $arr[$p];
            BlogmillRouteFunctions::array_delete_by_key($arr, $p);
            $nth -= $p * $f;
        }

        $result = array_merge($result, $arr);
        return $result;
    }
    private function array_delete_by_key(&$array, $delete_key, $use_old_keys = false) {
        unset($array[$delete_key]);
        if (!$use_old_keys) {
            $array = array_values($array);
        }
        return true;
    }
    private function factorial($int) {
        if ($int < 2) {
            return 1;
        }
        for ($f=2; $int -1 > 1; $f *= $int--);
        return $f;
    }
}