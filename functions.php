<?php

function count_task($tasks, $project) {
    if ($project == "Все")
        return count($tasks);
    $count = 0;
    foreach ($tasks as $task) {
        if ($task['project'] == $project)
            $count++;
    }
    return $count;
}

function check_deadline($date) {
    if ($date) {
        $task_deadline_ts = strtotime($date);
        $current_ts = time();
        $days_until_deadline = floor(($task_deadline_ts - $current_ts) / 86400);
        return $days_until_deadline <= 1;
    } else
        return false;
}

function clean_tags(&$item) {
    $item = htmlspecialchars($item);
}

function include_template($template_path, $data=NULL) {
    ob_start();

    if ( !is_null($data) ) {
        array_walk_recursive($data, "clean_tags");
        extract($data);
    }

    if ( file_exists($template_path) ) {
        include_once($template_path);
    } else {
        return '';
    }

    ob_end_flush();
}