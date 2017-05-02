<?php
require_once 'functions.php';

$data['project_id'] = ( $_GET['project'] ) ?? 0;
$data['projects'] = ["Все", "Входящие", "Учеба", "Работа", "Домашние дела", "Авто"];
$data['tasks'] = [
    [
        "title"     => "Собеседование в IT компании",
        "date"      => "01.06.2017",
        "project"   => "Работа",
        "completed" => false
    ],
    [
        "title"     => "Выполнить тестовое задание",
        "date"      => "25.05.2017",
        "project"   => "Работа",
        "completed" => false
    ],
    [
        "title"     => "Сделать задание первого раздела",
        "date"      => "21.04.2017",
        "project"   => "Учеба",
        "completed" => true
    ],
    [
        "title"     => "Встреча с другом",
        "date"      => "22.04.2017",
        "project"   => "Входящие",
        "completed" => false
    ],
    [
        "title"     => "Купить корм для кота",
        "date"      => "",
        "project"   => "Домашние дела",
        "completed" => false
    ],
    [
        "title"     => "Заказать пиццу",
        "date"      => "",
        "project"   => "Домашние дела",
        "completed" => false
    ],
];

 $count_task = function  ($tasks, $project) {
    if ($project == "Все")
        return count($tasks);
    $count = 0;
    foreach ($tasks as $task) {
        if ($task['project'] == $project)
            $count++;
    }
    return $count;
};

  $check_deadline = function ($date) {
    if ($date) {
        $task_deadline_ts = strtotime($date);
        $current_ts = time();
        $days_until_deadline = floor(($task_deadline_ts - $current_ts) / 86400);
        return $days_until_deadline <= 1;
    } else
        return false;
};

if ( ! isset( $data['projects'][ $data['project_id'] ] ) )
    header("HTTP/1.0 404 Not Found");

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
        echo include_template('main.php', $data );
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
