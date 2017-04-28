<?php


function clean_tags(&$item) {
    $item = htmlspecialchars($item, ENT_QUOTES, 'UTF-8' );
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

