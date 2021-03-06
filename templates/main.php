<div class="content">
    <section class="content__side">
        <h2 class="content__side-heading">Проекты</h2>

        <nav class="main-navigation">
            <ul class="main-navigation__list">
                <?php
                $keyToHightlight = !empty($_GET['project']) ? $_GET['project'] : 0;
                foreach ($templateData['projects'] as $key => $val):
                    $activeClass = '';
                    if ($key == $keyToHightlight) {
                        $activeClass = "main-navigation__list-item--active";
                    }
                    ?>
                    <li class="main-navigation__list-item <?= $activeClass; ?>">
                        <a class="main-navigation__list-item-link" href="/index.php?project=<?= $key; ?>"><?= htmlspecialchars($val); ?></a>
                        <span class="main-navigation__list-item-count"><?= getNumberTasks($templateData['allTasks'], htmlspecialchars($val)); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <a class="button button--transparent button--plus content__side-button" href="/index.php?add">Добавить проект</a>
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
            <input id="show-complete-tasks" class="checkbox__input visually-hidden"
                   type="checkbox" <?= $templateData['showCompletedChecked'] ?> >
            <span class="checkbox__text">Показывать выполненные</span>
            </label>
        </div>

        <table class="tasks">
            <?php
            foreach ( $templateData['tasksToDisplay'] as $key => $viewTask): ?>
                <tr class="tasks__item task <?= $viewTask[ VIEW_FIELD_completed_class ]; ?>" >
                    <td class="task__select">
                        <label class="checkbox task__checkbox">
                            <input class="checkbox__input visually-hidden" type="checkbox" checked>
                            <span class="checkbox__text"><?= htmlspecialchars($viewTask[VIEW_FIELD_title]); ?></span>
                        </label>
                    </td>
                    <td class="task__date"><?= htmlspecialchars($viewTask[VIEW_FIELD_date]); ?></td>
                    <td class="task__controls"></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
</div>
