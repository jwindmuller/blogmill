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

    public function postIndex($url, $types) {
        $perms = array_permutations($types);
        foreach( $perms as $i => $perm ) {
            $type = join(',', $perm);
            Router::connect($url, array('controller' => 'posts', 'action' => 'index', $type));
        }
        
    }

}
