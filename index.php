<?php
session_start();
error_reporting(E_ALL);
require_once 'functions.php';
require_once 'data/user_data.php';
require_once 'data/project_data.php';

$user = [];
$bodyClassOverlay = '';
$modalShow = false;
$showAuthenticationForm = false;
if (isset($_GET['login']) || isset($_POST['sendAuth'])) {
    $bodyClassOverlay = 'overlay';
    $showAuthenticationForm = true;
}

$dataForHeaderTemplate = AddkeysForValidation(['email', 'password']);

if (isset($_POST['sendAuth'])) {

    $resultAuth = validateLoginForm($users);

    if (!$resultAuth['error']) {
        if (password_verify($_POST['password'], $resultAuth['user']['password'])) {
            $_SESSION['user'] = $resultAuth['user'];
            header("Location: /index.php");
            exit();
        } else {
            $resultAuth['output']['errors']['password'] = true;
        }
    }
    $dataForHeaderTemplate = $resultAuth['output'];
}

$user = (isset($_SESSION['user'])) ? $_SESSION['user'] : [];

if (isset($_GET['add']) || isset($_POST['send'])) {
    $bodyClassOverlay = 'overlay';
    $modalShow = true;
}

$allTasks = getSourceTasks();
$projects = getSourceProjects();
$tasksToDisplay = filterTasks( $allTasks, $projects );


$expectedFields = ['title', 'project', 'date'];

$newTask = ['completed' => 'Нет'];
$errors = [];
foreach ($expectedFields as $field) {
    $newTask[$field] = '';
    $errors[$field] = false;
}
if (isset($_POST['send'])) {
    $errorsFound = false;
    foreach ($expectedFields as $name) {
        if (!empty($_POST[$name])) {
            $newTask[$name] = sanitizeInput($_POST[$name]);
        } else {
            $errors[$name] = true;
            $errorsFound = true;
        }
    }
    if (!$errorsFound) {
        array_unshift($tasksToDisplay, $newTask);
        $bodyClassOverlay = '';
        $modalShow = false;
    }
    if (isset($_FILES['preview'])) {
        $file = $_FILES['preview'];
        if (is_uploaded_file($file['tmp_name'])) {
            move_uploaded_file($file['tmp_name'], __DIR__ . '/upload/' . $file['name']);
        }
    }
}

if (isset($_SESSION['user']) and !(isset($_GET['add']) || isset($_POST['send']))) {
    $bodyClassOverlay = '';
}

ifRequestForShowCompleted();

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

<body class=<?= $bodyClassOverlay; ?>>
<h1 class="visually-hidden">Дела в порядке</h1>

<div class="page-wrapper">
    <div class="container container--with-sidebar">
        <?= includeTemplate('header.php', ['user' => $user]); ?>
        <?php
        if (!$user) {
            print(includeTemplate('guest.php', $dataForHeaderTemplate + ['showAuthenticationForm' => $showAuthenticationForm]));
        } else {
            print (includeTemplate('main.php', ['projects' => $projects, 'tasksToDisplay' => getViewTasks( $tasksToDisplay ), 'allTasks' => $allTasks, 'show_completed' => showWithCompleted(), 'checked' => $checked, 'hidden' => $hidden]));
        }
        ?>
    </div>
</div>

    <?php
        print includeTemplate('footer.php', ['user' => $user]);
        if ($modalShow) {
            print(includeTemplate('add_project.php', ['errors' => $errors, 'projects' => $projects, 'newTask' => $newTask]));
        }
    ?>

<script type="text/javascript" src="js/script.js"></script>
</body>
</html>
