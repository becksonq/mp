<?php

use yii\helpers\Url;

ini_set( 'error_reporting', E_ALL );
ini_set( 'display_errors', 1 );
ini_set( 'display_startup_errors', 1 );

set_time_limit( 0 );

//$session = Yii::$app->session;
//$session->open();

?>

<div class="mailparsing-default-index">

	<?php if ( $message == 1 ) { ?>
		<p>Вложения получены. Массив файлов находится в переменной сессии $session['messages'].<br>
			<a href="<?= Url::to( ['default/get-parts']) ?>">Продолжить</a>
		</p>
	<?php } ?>
</div>
