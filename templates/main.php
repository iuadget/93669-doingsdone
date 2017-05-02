<div class="content">
    <section class="content__side">
        <h2 class="content__side-heading">Проекты</h2>

        <nav class="main-navigation">
            <ul class="main-navigation__list">
                <?php foreach ($projects as $key => $project) : ?>
                    <li class="main-navigation__list-item <?php if ($key == 0) echo "main-navigation__list-item--active"; ?>
                        ">
                        <a class="main-navigation__list-item-link" href="/index.php?project=<?php echo $key; ?>"><?php echo $project; ?></a>
                        <span class="main-navigation__list-item-count"><?php echo count_task($tasks, $project); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <a class="button button--transparent button--plus content__side-button" href="#">Добавить проект</a>
    </section>

    <main class="content__main">
        <h2 class="content__main-heading">Список задач</h2>

        <form class="search-form" action="index.php" method="post">
            <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

            <input class="search-form__submit" type="submit" name="" value="Искать">
        </form>

        <div class="tasks-controls">
            <nav class="tasks-switch">
                <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
                <a href="/" class="tasks-switch__item">Повестка дня</a>
                <a href="/" class="tasks-switch__item">Завтра</a>
                <a href="/" class="tasks-switch__item">Просроченные</a>
            </nav>

            <label class="checkbox">
                <input id="show-complete-tasks" class="checkbox__input visually-hidden" type="checkbox" checked>
                <span class="checkbox__text">Показывать выполненные</span>
            </label>
        </div>


        <?php
        if ( isset( $projects[ $project_id ] ) ) : ?>

            <table class="tasks">

            <?php foreach ($tasks as $task) : ?>
                <tr class="tasks__item task <?php if ($task['completed']) {
                    echo "task--completed"; }
                    else if (check_deadline($task['date'])) {
                    echo "task--important";}
                if ( $task['project'] != $projects[ $project_id ] && $project_id != 0 ) {
                    echo " hidden";} ?>">
                    <td class="task__select">
                        <label class="checkbox task__checkbox">
                            <input class="checkbox__input visually-hidden" type="checkbox">
                            <span class="checkbox__text"><?php echo $task['title']; ?></span>
                        </label>
                    </td>

                    <td class="task__date">
                        <?php echo ($task['date']) ? $task['date'] : "Нет"; ?>
                    </td>

                    <td class="task__controls">
                        <button class="expand-control" type="button" name="button"><?php echo $task['title']; ?></button>

                        <ul class="expand-list hidden">
                            <li class="expand-list__item">
                                <a href="#">Выполнить</a>
                            </li>

                            <li class="expand-list__item">
                                <a href="#">Удалить</a>
                            </li>

                            <li class="expand-list__item">
                                <a href="#">Дублировать</a>
                            </li>
                        </ul>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>
        <?php else : ?>
            <div>Задач не найдено</div>
        <?php endif; ?>
    </main>
</div>