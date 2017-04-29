<?php


function clean_tags(&$item) {
    if (!($item and (is_string($item) || is_array($item)))) {
        return $item;
    }
    if (is_array($item)) {
        return array_map(function ($item) {
            return xss_clean($item);
        }, $item);
    }
    $item = htmlspecialchars($item);
    return $item;
}

function include_template($filename, $data = []) {

    $filename = 'templates/' . $filename;

    if ( ! file_exists( $filename ) || ! is_readable( $filename ) ) {
        return '';
    }

    array_walk_recursive( $data, 'clean_tags' );

    extract( $data );

    ob_start();

    require_once $filename;

    return ob_get_clean();

}

