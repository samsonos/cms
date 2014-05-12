<?php
function dictionary()
{
	/* Global plural form dictionary */
	$GLOBALS['dict_plural'] = array(
		'новая' 	=> array('новая', 'новые', 'новых' ),
		'секунда' 	=> array('секунда','секунды','секунд'),
		'минута' 	=> array('минута','минуты','минут'),
		'час' 		=> array('час','часа','часов'),
		'день' 		=> array('день','дня','дней'),
		'месяц' 	=> array('месяц','месяца','месяцев'),
		'год' 		=> array('год','года','лет'),
		'голос' 	=> array('голос','голоса','голосов'),
	);
	
	return array( 
		'en' => array( 
			'Описание' => 'Description',
			'Поиск' => 'Search',
			'голосов' => 'votes',
			'голоса' => 'votes',
			'голос' => 'vote',
			'загрузить' => 'upload',
			'свой' => 'your',
			'ремикс' => 'remix',
			'скачать remix pack' => 'download remix pack',
			'правила' => 'rules',
			'добавить remix'=> 'upload your remix',
			'Правила' => 'Rules',
			'Закрыть' => 'Close',
			'Подтвердите ваши данные' => 'Verification personal data',
			'Ник' => 'Artist name',
			'Местоположение' => 'Location',
			'Ссылка на FB' => 'FB link',
			'Отмена' => 'Cancel',
			'Загрузите свой ремикс' => 'Upload your remix',
			'Обзор' => 'Choose file',
			'Стиль' => 'Genre',
			'Для продолжения авторизуйтесь с помощью Facebook' => 'To continue, login with Facebook',
		), 
	);
}