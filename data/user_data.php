<?php
define( 'USER_FIELD_email', 'email' );
define( 'USER_FIELD_name', 'name' );
define( 'USER_FIELD_password', 'password' );
// пользователи для аутентификации
function getSourceUsers()
{
	return [
		[
			USER_FIELD_email => 'ignat.v@gmail.com',
			USER_FIELD_name => 'Игнат',
			USER_FIELD_password => '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka'
		],
		[
			USER_FIELD_email => 'kitty_93@li.ru',
			USER_FIELD_name => 'Леночка',
			USER_FIELD_password => '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa'
		],
		[
			USER_FIELD_email => 'warrior07@mail.ru',
			USER_FIELD_name => 'Руслан',
			USER_FIELD_password => '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW'
		]
	];
}