<?php
session_start();
error_reporting(E_ALL);
require_once 'functions.php';
require_once 'data/user_data.php';
require_once 'data/project_data.php';

$user = (isset($_SESSION['user'])) ? $_SESSION['user'] : null;

$validationResult = null;
list( $loginFields, $loginErrors ) = actionRequestForLogin();

$allTasks = getSourceTasks();
$projects = getSourceProjects();
$tasksToDisplay = filterTasks( $allTasks, $projects );
$newTask = getEmptyTask();
$taskAddErrors = [];
list($tasksToDisplay, $newTask, $taskAddErrors) = actionAddTask( $tasksToDisplay, $newTask );

actionShowCompleted();

$checked = '';
$hidden = 'hidden';
if (isset($_COOKIE['show_completed'])) {
    $checked = 'checked';
    $hidden = '';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Дела в Порядке!</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class=<?= getBodyClassOverlay( $taskAddErrors, $loginErrors ); ?>>
<h1 class="visually-hidden">Дела в порядке</h1>

<div class="page-wrapper">
    <div class="container container--with-sidebar">
        <?= includeTemplate('header.php', ['user' => $user]); ?>
        <?php
        if (!$user) {
            print(includeTemplate('guest.php', [
                'showAuthenticationForm' => ( isRequestForShowLoginForm() || count( $loginErrors )),
                'errors' => $loginErrors,
                'fields' => $loginFields
            ]));
        } else {
            print (includeTemplate('main.php', [
                'projects' => $projects,
                'tasksToDisplay' => getViewTasks( $tasksToDisplay ),
                'allTasks' => $allTasks,
                'show_completed' => showWithCompleted(),
                'checked' => $checked,
                'hidden' => $hidden
            ]));
        }
        ?>
    </div>
</div>

    <?php
        print includeTemplate('footer.php', ['user' => $user]);
        if (isRequestForShowAddTaskForm() || count( $taskAddErrors )) {
            print(includeTemplate('add_project.php', [
                'errors' => $taskAddErrors,
                'projects' => $projects,
                'newTask' => $newTask
            ]));
        }
    ?>

<script type="text/javascript" src="js/script.js"></script>
</body>
</html>
