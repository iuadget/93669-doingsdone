<?php

function searchUserByEmail($email, $users)
{
    $result = null;
    foreach ($users as $user) {
        if ($user['email'] == $email) {
            $result = $user;
            break;
        }
    }
    return $result;
}

function validateLoginForm($users)
{
    $errors = false;
    $user = null;
    $fields = ['email', 'password'];
    $output = AddkeysForValidation($fields);
    foreach ($fields as $name) {
        if (!empty($_POST[$name]) && $user = searchUserByEmail($_POST['email'], $users)) {
            $output['valid'][$name] = sanitizeInput($_POST[$name]);
        } else {
            $output['errors'][$name] = true;
            $errors = true;
        }
    }
    return ['error' => $errors, 'output' => $output, 'user' => $user];
}

function addRequiredSpan($errors, $name)
{
    if ($errors[$name]) {
        return("<span>Обязательное поле</span>");
    }
}

function AddkeysForValidation($keysField)
{
    $result = [];
    foreach ($keysField as $field) {
        $result['valid'][$field] = '';
        $result['errors'][$field] = false;
    }
    return $result;
}

function setClassError($errors, $name)
{
    return ($errors[$name]) ? 'form__input--error' : '';
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function includeTemplate($template, $templateData) {
    if (!isset($template)) {
        return "";
    }
    ob_start();

    require_once __DIR__ . "/templates/$template";

    return ob_get_clean();
}

function getNumberTasks($tasks, $nameCategory) {
    if (!$nameCategory) {
        return 0;
    }
    if ($nameCategory == "Все") {
        return count($tasks);
    }

    $countTask = 0;
    foreach ($tasks as $key => $value) {
        if ($value["project"] == $nameCategory) {
            $countTask ++;
        }
    }
    return $countTask;
}

function getFormValue($templateData, $name)
{
    if ($name == 'project') {
        $result = 'Входящие';
        if ($templateData['newTask']['project']) {
            $result = $templateData['newTask']['project'];
        }
        return $result;
    }
    return $templateData['newTask'][$name];
}
