<?php
define( 'VIEW_FIELD_title', 'title' );
define( 'VIEW_FIELD_date', 'date' );
define( 'VIEW_FIELD_project', 'project' );
define( 'VIEW_FIELD_completed_class', 'completed' );


/**
 * @param $email
 * @param $users
 * @return null|array
 */
function searchUserByEmail($email, $users)
{
    foreach ($users as $user) {
        if ($user[USER_FIELD_email] == $email) {
            return $user;
        }
    }
    return null;
}

define( 'LOGIN_ERROR_EMPTY_EMAIL', 1 );
define( 'LOGIN_ERROR_USER_NOT_FOUND', 2 );
define( 'LOGIN_ERROR_EMPTY_PASSWORD', 3 );
define( 'LOGIN_ERROR_INCORRECT_PASSWORD', 4 );
define( 'LOGIN_ERROR_NO_ERROR', 0 );

/**
 * @return array
 */
function checkLoginForm()
{
    if ( empty( $_POST['email'] ) )
    	return [ null, LOGIN_ERROR_EMPTY_EMAIL ];

    if ( ! $user = searchUserByEmail( $_POST['email'], getSourceUsers() ) )
    	return [ null, LOGIN_ERROR_USER_NOT_FOUND ];

    if ( empty ($_POST['password'] ) )
    	return [ $user, LOGIN_ERROR_EMPTY_PASSWORD ];

    if ( ! password_verify($_POST['password'], $user[USER_FIELD_password]))
    	return [ $user, LOGIN_ERROR_INCORRECT_PASSWORD ];

    return [ $user, LOGIN_ERROR_NO_ERROR ];
}

function addRequiredSpan($errors, $name, $text = '', $textFromErrors = false)
{
    if (! isset($errors[$name]))
	{
		return '';
	}
	if (!$text)
		$text = "<span>Обязательное поле</span>";

    return sprintf( "<p class='form__message'>%s</span>", $textFromErrors ? $errors[ $name ] : $text );
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
    return (isset($errors[$name])) ? 'form__input--error' : '';
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

function isRequestForShowCompleted()
{
	return isset( $_GET['show_completed'] );
}

/**
 * @return void
 */
function actionShowCompleted()
{
	if( isRequestForShowCompleted() )
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

	if (isRequestForShowCompleted())
		return (bool)$_GET['show_completed'];

	return false;
}

/**
 * @param array $tasks
 * @param array $projects
 *
 * @return array
 */
function filterTasks( array $tasks, array $projects )
{
	$tasks = filterTasksByProjectId( $tasks, $projects );

	$tasks = filterTasksByCompleted( $tasks );

	return $tasks;
}

/**
 * @param array $tasks
 * @param array $projects
 * @return array
 */
function filterTasksByProjectId( array $tasks, array $projects )
{
	if ( ! isset($_GET['project']))
	{
		return $tasks;
	}

	$project = (int) abs(($_GET['project']));

	if ( ! isset( $projects[ $project ] ) ) {
		header('HTTP/1.0 404 Not Found');
		exit( '404 - Not Found' );
	}

	return array_filter($tasks, function($task) use ($projects, $project) {
		return $project == 0 || $projects[$project] == $task['project'];
	});

}

/**
 * @param array $tasks
 * @return array
 */
function filterTasksByCompleted( array $tasks )
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

function isRequestForShowAddTaskForm()
{
	return isset($_GET['add']);
}

function isRequestForAddTask()
{
	return (isset($_POST['send']));
}

/**
 * @param array $tasks
 * @param array $emptyTask
 * @return array
 */
function actionAddTask( array $tasks, array $emptyTask )
{
	if ( ! isRequestForAddTask() )
		return [ $tasks, $emptyTask, [] ];

	$expectedFields = [
		'title' => FIELD_title,
		'project' => FIELD_project,
		'date' => FIELD_date
	];

	$errors = [];
	foreach (array_keys($expectedFields) as $field) {
		if ( ! isset( $_POST[$field] ) )
			$errors[$field] = true;

		if ( empty( $_POST[$field] ) )
			$errors[$field] = true;
	}

	if ( count( $errors ) )
		return [ $tasks, $emptyTask, $errors ];

	$newTask = $emptyTask;
	foreach ( $expectedFields as $postField => $taskField )
	{
		$newTask[$taskField] = $_POST[$postField];
	}

	array_unshift( $tasks, $newTask );

	if (isset($_FILES['preview'])) {
		$file = $_FILES['preview'];
		if (is_uploaded_file($file['tmp_name'])) {
			move_uploaded_file($file['tmp_name'], __DIR__ . '/upload/' . $file['name']);
		}
	}

	return [ $tasks, $newTask, [] ];
}

/**
 * @param array $taskAddErrors
 * @param array $loginErrors
 * @return string
 */
function getBodyClassOverlay( $taskAddErrors, $loginErrors )
{
	switch (true)
	{
		case isRequestForShowAddTaskForm():
		case count( $taskAddErrors ):
		case isRequestForShowLoginForm():
		case count( $loginErrors ):
			return 'overlay';
	}

	return '';
}

function isRequestForShowLoginForm()
{
	return isset($_GET['login']);
}

function isRequestForLogin()
{
	return isset($_POST['sendAuth']);
}

/**
 * @return array
 */
function actionRequestForLogin()
{
	$fields = [
		'email' => '',
		'password' => '',
	];

	if ( ! isRequestForLogin() )
	{
		return [ $fields, [] ];
	}

	list( $user, $validationResult ) = checkLoginForm();

	if ( $validationResult === LOGIN_ERROR_NO_ERROR )
	{
		$_SESSION['user'] = $user;
		header("Location: /index.php");
		exit();
	}

	foreach ( $fields as $fieldName => $field )
	{
		if ( isset( $_POST[ $fieldName ] ) )
			$fields[ $fieldName ] = $_POST[ $fieldName ];
	}

	return [ $fields, getValidationExplain( $validationResult, [] ) ];
}

/**
 * @param int $validationResult
 * @param array $errors
 *
 * @return array
 */
function getValidationExplain( $validationResult, $errors )
{
	switch ( $validationResult )
	{
		case LOGIN_ERROR_NO_ERROR:
			break;
		case LOGIN_ERROR_INCORRECT_PASSWORD:
			$errors[ 'password' ] = 'Пароль введён некорректно';
			break;
		case LOGIN_ERROR_EMPTY_PASSWORD:
			$errors[ 'password' ] = 'Пароль - обязательно поле';
			break;
		case LOGIN_ERROR_USER_NOT_FOUND:
			$errors[ 'email' ] = 'Пользователь не найден';
			break;
		case LOGIN_ERROR_EMPTY_EMAIL:
			$errors[ 'email' ] = 'Email - обязательное поле';
			break;
	}

	return $errors;
}