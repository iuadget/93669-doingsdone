<?php
define('P_ALL', 0);
define('P_INCOME', 1);
define('P_LEARN', 2);
define('P_WORK', 3);
define('P_HOME', 4);
define('P_AUTO', 5);

/**
 * @return array
 */
function getSourceProjects()
{
	return [
		P_ALL => 'Все',
		P_INCOME => 'Входящие',
		P_LEARN => 'Учеба',
		P_WORK => 'Работа',
		P_HOME => 'Домашние дела',
		P_AUTO => 'Авто',
	];
}

function getEmptyTask()
{
	return [
		FIELD_title     => '',
		FIELD_date      => '',
		FIELD_project   => '',
		FIELD_completed => FIELD_VALUE_INCOMPLETE
	];
}

/**
 * @return array
 */
function getSourceTasks()
{
	return [
		[
			FIELD_title     => 'Собеседование в IT компании',
			FIELD_date      => '01.06.2017',
			FIELD_project   => 'Работа',
			FIELD_completed => FIELD_VALUE_INCOMPLETE
		],
		[
			FIELD_title     => 'Выполнить тестовое задание',
			FIELD_date      => '25.05.2017',
			FIELD_project   => 'Работа',
			FIELD_completed => FIELD_VALUE_INCOMPLETE
		],
		[
			FIELD_title     => 'Сделать задание первого раздела',
			FIELD_date      => '21.04.2017',
			FIELD_project   => 'Учеба',
			FIELD_completed => FIELD_VALUE_COMPLETE
		],
		[
			FIELD_title     => 'Встреча с другом',
			FIELD_date      => '22.04.2017',
			FIELD_project   => 'Входящие',
			FIELD_completed => FIELD_VALUE_INCOMPLETE
		],
		[
			FIELD_title     => 'Купить корм для кота',
			FIELD_date      => FIELD_DATE_NULL,
			FIELD_project   => 'Домашние дела',
			FIELD_completed => FIELD_VALUE_INCOMPLETE
		],
		[
			FIELD_title     => 'Заказать пиццу',
			FIELD_date      => FIELD_DATE_NULL,
			FIELD_project   => 'Домашние дела',
			FIELD_completed => FIELD_VALUE_INCOMPLETE
		]
	];

}