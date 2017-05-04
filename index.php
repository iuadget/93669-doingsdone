<?php
require_once 'functions.php';

define('P_ALL', 0);
define('P_INCOME', 1);
define('P_LEARN', 2);
define('P_WORK', 3);
define('P_HOME', 4);
define('P_AUTO', 5);

$current_ts = time();

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
        'title'    => 'Собеседование в IT компании',
        'date'     => '01.06.2017',
        'project'  => P_WORK,
        'completed'=> false,
    ],
    [
        'title'    => 'Выполнить тестовое задание',
        'date'     => '25.05.2017',
        'project'  => P_WORK,
        'completed'=> false,
    ],
    [
        'title'    => 'Сделать задание первого раздела',
        'date'     => '21.04.2017',
        'project'  => P_LEARN,
        'completed'=> true,
    ],
    [
        'title'    => 'Встреча с другом',
        'date'     => '22.04.2017',
        'project'  => P_INCOME,
        'completed'=> false,
    ],
    [
        'title'    => 'Купить корм для кота',
        'date'     => '',
        'project'  => P_HOME,
        'completed'=> false,
    ],
    [
        'title'    => 'Заказать пиццу',
        'date'     => '',
        'project'  => P_HOME,
        'completed'=> false,
    ],
];

$current_project = get_current_code();
if (!get_project_name ($projects, $current_project)) {
    header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    exit;
}

$get_tasks_project = function ( $tasks,  $project) {
    if ($project == P_ALL) {
        return count($tasks);
    }
    $count = 0;
    foreach ($tasks as $task) {
        if ($task['project'] == $project) {
            $count++;
        }
    }
    return $count;
};

$get_tasks_code = function ( $tasks,  $project) {
    if ($project == P_ALL) {
        return $tasks;
    }
    return array_filter($tasks, function ($task) use ($project) {
        return ($task['project'] == $project);
    });
};

function get_current_code()
{
    return (isset($_GET['project']) ? (int) $_GET['project'] : P_ALL);
}

function get_project_name(array $projects, $project)
{
    return (isset($projects[$project]) ? $projects[$project] : null);
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

<body><!--class="overlay"-->
<h1 class="visually-hidden">Дела в порядке</h1>

<div class="page-wrapper">
    <div class="container container--with-sidebar">
        <?php
        echo include_template('header.php');
        echo include_template('main.php', [
            'current_ts' => $current_ts,
            'projects' => $projects,
            'tasks' => $tasks,
            'current_project' => $current_project,
            'get_tasks_project' => $get_tasks_project,
            'get_tasks_code' => $get_tasks_code,
            ]);
        ?>
    </div>
</div>

<?php echo include_template('footer.php'); ?>

<div class="modal" hidden>
    <button class="modal__close" type="button" name="button">Закрыть</button>

    <h2 class="modal__heading">Добавление задачи</h2>

    <form class="form" class="" action="index.html" method="post">
        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>

            <input class="form__input" type="text" name="name" id="name" value="" placeholder="Введите название">
        </div>

        <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>

            <select class="form__input form__input--select" name="project" id="project">
                <option value="">Входящие</option>
            </select>
        </div>

        <div class="form__row">
            <label class="form__label" for="date">Дата выполнения <sup>*</sup></label>

            <input class="form__input form__input--date" type="text" name="date" id="date" value="" placeholder="Введите дату в формате ДД.ММ.ГГГГ">
        </div>

        <div class="form__row">
            <label class="form__label" for="file">Файл</label>

            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="preview" id="preview" value="">

                <label class="button button--transparent" for="preview">
                    <span>Выберите файл</span>
                </label>
            </div>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>
    </form>
</div>

<script type="text/javascript" src="js/script.js"></script>
</body>
</html>
