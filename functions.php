<?php

function check_deadline( $date ) {
    if ( $date ) {
        $task_deadline_ts    = strtotime( $date );
        $current_ts          = time();
        $days_until_deadline = floor( ( $task_deadline_ts - $current_ts ) / 86400 );

        return $days_until_deadline <= 0;
    } else {
        return false;
    }
}

function count_task( $tasks, $project ) {
    if ( $project == "Все" ) {
        return count( $tasks );
    }

    $count = 0;

    foreach ( $tasks as $task ) {
        if ( $task['project'] == $project ) {
            $count ++;
        }
    }

    return $count;
}

function clean_tags(&$item) {

    if (!($item && (is_string($item) || is_array($item)))) {
        return $item;
    }

    if (is_array($item)) {
        return array_map(function ($item) {
            return clean_tags($item);
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

