<?php
ob_start();
error_reporting(E_ALL);
require_once 'functions.php';

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

$bodyClassOverlay = '';
$modalShow = false;
if (isset($_GET['add']) || isset($_POST['send'])) {
    $bodyClassOverlay = 'overlay';
    $modalShow = true;
}

$expectedFields = ['task', 'project', 'date'];

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
        <?= includeTemplate('header.php', []); ?>
        <?= includeTemplate('main.php', [
            'projects' => $projects,
            'tasksToDisplay' => $tasksToDisplay,
            'allTasks' => $tasks
        ]); ?>
    </div>
</div>
<?= includeTemplate('footer.php', []); ?>

<?php
if ($modalShow) {
    print(includeTemplate('add_project.php', [
        'errors' => $errors,
        'projects' => $projects,
        'newTask' => $newTask]));
} ?>
<script type="text/javascript" src="js/script.js"></script>
</body>
</html>
