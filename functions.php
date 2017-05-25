<?php
define( 'FIELD_title', 'title' );
define( 'FIELD_date', 'date' );
define( 'FIELD_project', 'project' );
define( 'FIELD_completed', 'completed' );

define('FIELD_VALUE_COMPLETE', 1);
define('FIELD_VALUE_INCOMPLETE', 0 );
define('FIELD_DATE_NULL', null );

define( 'VIEW_FIELD_title', 'title' );
define( 'VIEW_FIELD_date', 'date' );
define( 'VIEW_FIELD_project', 'project' );
define( 'VIEW_FIELD_completed_class', 'completed' );

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
            $output['valid'][$name] = ($_POST[$name]);
        } else {
            $output['errors'][$name] = true;
            $errors = true;
        }
    }
    return ['error' => $errors, 'output' => $output, 'user' => $user];
}

function addRequiredSpan($errors, $name, $text = '')
{
    if ($errors[$name]) {
        if ($text) {
            print("<p class='form__message'>$text</span>");
        } else {
            print("<span>Обязательное поле</span>");
        }
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

function arrayDelRealizedTask(array $tasks): array
{
    foreach ($tasks as $index => $task) {
        if ($task['completed']) {
            unset($tasks[$index]);
        }
    }
    return $tasks;
}

/**
 * @return void
 */
function ifRequestForShowCompleted()
{
	if( isset( $_GET['show_completed'] ) )
		setShowCompleted( $_GET[ 'show_completed' ] );
}

/**
 * @param mixed $showCompleted
 *
 * @return void
 */
function setShowCompleted( $showCompleted )
{
	if ( (bool)$showCompleted )
		setcookie('show_completed',  (string)$showCompleted, strtotime("+30 days"), '/');
	else
		setcookie('show_completed', '', 0, '/' );
}

/**
 * @return bool
 */
function showWithCompleted()
{
	if (isset($_COOKIE['show_completed']))
		return (bool)$_COOKIE['show_completed'];

	if (isset( $_GET['show_completed'] ))
		return (bool)$_GET['show_completed'];

	return false;
}

/**
 * @param array $tasks
 * @return array
 */
function filterTasks( array $tasks )
{
	$return = [];
	while( count( $tasks ) )
	{
		$task = array_shift( $tasks );
		if ( ! showWithCompleted() && $task['completed'] === FIELD_VALUE_COMPLETE )
			continue;

		$return[] = $task;
	}

	return $return;
}

/**
 * @param array $tasks
 * @return array
 */
function getViewTasks( array $tasks )
{
	$result = [];
	while( count( $tasks ) )
	{
		$task = array_shift( $tasks );
		$viewTask = [
			VIEW_FIELD_title => $task[FIELD_title],
			VIEW_FIELD_project => $task[FIELD_project],
			VIEW_FIELD_date => ($task[FIELD_date] === FIELD_DATE_NULL) ? 'Нет' : $task[FIELD_date],
			VIEW_FIELD_completed_class => ($task[FIELD_completed] === FIELD_VALUE_COMPLETE) ? 'task--completed' : ''
		];

		$result[] = $viewTask;
	}

	return $result;
}