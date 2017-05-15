<div class="modal">
    <a href="/" class="modal__close">Закрыть</a>

    <h2 class="modal__heading">Добавление задачи</h2>

    <form class="form" class="" action="/index.php" method="post" enctype="multipart/form-data">
        <div class="form__row">
            <label class="form__label" for="title">Название <sup>*</sup></label>
            <?= addRequiredSpan($templateData['errors'], 'title'); ?>
            <input class="form__input <?= setClassError($templateData['errors'], 'title'); ?>" type="text" name="title" id="name" value="<?= getFormValue($templateData, 'title'); ?>" placeholder="Введите название">
        </div>

        <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>
            <?= addRequiredSpan($templateData['errors'], 'project'); ?>
            <select class="form__input form__input--select  <?= setClassError($templateData['errors'], 'project'); ?>" name="project" id="project">
                <?php
                $selectedValue = getFormValue($templateData, 'project');
                $allOptions = array_merge([0 => 'Выберите проект'], array_combine($templateData['projects'], $templateData['projects']));
                foreach ($allOptions as $value => $option) {
                    $selected = $option == $selectedValue ? 'selected' : '';
                    echo '<option value="' . $value . '" ' . $selected . '>' . $option . '</option>';
                }
                ?>
            </select>
        </div>

        <div class="form__row">
            <label class="form__label" for="date">Дата выполнения <sup>*</sup></label>
            <?= addRequiredSpan($templateData['errors'], 'date'); ?>
            <input class="form__input form__input--date <?= setClassError($templateData['errors'], 'date'); ?>" type="text" name="date" id="date" value="<?= $templateData['newTask']['date']; ?>" placeholder="Введите дату в формате ДД.ММ.ГГГГ">
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
            <input class="button" type="submit" name="send" value="Добавить">
        </div>
    </form>
</div>

