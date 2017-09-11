<?php

use yii\helpers\Url;

ini_set( 'error_reporting', E_ALL );
ini_set( 'display_errors', 1 );
ini_set( 'display_startup_errors', 1 );

set_time_limit( 0 );


?>

<div class="mailparsing-default-index">

	<?php if ( $message == 1 ) { ?>
		<p>Вложения получены. Массив файлов находится в переменной сессии $session['messages'].<br>
			<a href="<?= Url::to( [ 'default/get-parts' ] ) ?>">Продолжить</a>
		</p>
	<?php }
	if ( $message == 2 ) { ?>
		<p>
			Вложения разобраны.<br><br>

			<?php
			/**
			 * @var $html \app\modules\mailparsing\controllers\DefaultController
			 */
			foreach ( $html as $item ) {
				?>
				ID: <?= $item['id'] ?><br>
				Date: <? $item['date'] ?><br>
				Time: <? $item['time'] ?><br>
				From: <?= $item['from'] ?><br>
				To: <?= $item['to'] ?><br>
				Title: <?= $item['title'] ?><br><br>

			<?php }
			?>
			<br>
			Записать данные в таблицу: <a href="<?= Url::to( [ 'default/write-data-to-table' ] ) ?>">Go!</a>
		</p>
		<?php
	}
	?>
</div>
