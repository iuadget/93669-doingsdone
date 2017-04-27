<?php
$projects = ["Все", "Входящие", "Учеба", "Работа", "Домашние дела", "Авто"];
$tasks = [
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

require_once("./functions.php");

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
        include_template("./templates/header.php");
        include_template("./templates/main.php", ["projects" => $projects, "tasks" => $tasks]);
        ?>
    </div>
</div>

<?php include_template("./templates/footer.php"); ?>

<script type="text/javascript" src="js/script.js"></script>
</body>
</html>
