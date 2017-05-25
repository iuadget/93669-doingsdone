<?php
session_start();
error_reporting(E_ALL);
require_once 'functions.php';
require_once 'userdata.php';

define('P_ALL', 0);
define('P_INCOME', 1);
define('P_LEARN', 2);
define('P_WORK', 3);
define('P_HOME', 4);
define('P_AUTO', 5);

$projects = [
    P_ALL    => 'Все',
    P_INCOME => 'Входящие',
    P_LEARN  => 'Учеба',
    P_WORK   => 'Работа',
    P_HOME   => 'Домашние дела',
    P_AUTO   => 'Авто',
];
$tasks = [
    [
        'title'     => 'Собеседование в IT компании',
        'date'      => '01.06.2017',
        'project'   => 'Работа',
        'completed' => 'Нет'
    ],
    [
        'title'     => 'Выполнить тестовое задание',
        'date'      => '25.05.2017',
        'project'   => 'Работа',
        'completed' => 'Нет'
    ],
    [
        'title'     => 'Сделать задание первого раздела',
        'date'      => '21.04.2017',
        'project'   => 'Учеба',
        'completed' => 'Да'
    ],
    [
        'title'     => 'Встреча с другом',
        'date'      => '22.04.2017',
        'project'   => 'Входящие',
        'completed' => 'Нет'
    ],
    [
        'title'     => 'Купить корм для кота',
        'date'      => 'Нет',
        'project'   => 'Домашние дела',
        'completed' => 'Нет'
    ],
    [
        'title'     => 'Заказать пиццу',
        'date'      => 'Нет',
        'project'   => 'Домашние дела',
        'completed' => 'Нет'
    ]
];

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

$tasksToDisplay = [];
$project = '';
if (isset($_GET['project'])) {
    $project = (int) abs(($_GET['project']));

    if ($project > count($tasks) - 1) {
        header('HTTP/1.0 404 Not Found');
        exit();
    } else {
        $tasksToDisplay = array_filter($tasks, function($task) use ($projects, $project) {
            return $project == 0 || $projects[$project] == $task['project'];
        });
    }
} else {
    $tasksToDisplay = $tasks;
}

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

$show_completed = false;
if (isset($_GET['show_completed'])) {
    $show_completed = sanitizeInput($_GET['show_completed']);
    setcookie('show_completed', $show_completed, strtotime("+30 days"));
} else if (isset($_COOKIE['show_completed'])) {
    $show_completed = $_COOKIE['show_completed'];
}

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
            print (includeTemplate('main.php', ['projects' => $projects, 'tasksToDisplay' => $tasksToDisplay, 'allTasks' => $tasks, 'show_completed' => $show_completed, 'checked' => $checked, 'hidden' => $hidden]));
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
