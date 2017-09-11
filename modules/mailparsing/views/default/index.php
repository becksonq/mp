<?php

use yii\helpers\Url;
use \app\modules\mailparsing\models\H;

//ini_set( 'error_reporting', E_ALL );
//ini_set( 'display_errors', 1 );
//ini_set( 'display_startup_errors', 1 );

set_time_limit( 0 );


?>

<div class="mailparsing-default-index">

	<?php if ( isset( $message ) && $message == 1 ) { ?>
		<p>Вложения получены. Массив файлов находится в переменной сессии $session['messages'].<br>
			<a href="<?= Url::to( [ 'default/get-parts' ] ) ?>">Продолжить</a>
		</p>
	<?php }
	if ( isset( $message ) && $message == 2 ) { ?>
		<p>
			Вложения разобраны.<br><br>

			<?php
			/**
			 * @var $html \app\modules\mailparsing\controllers\DefaultController
			 */
			foreach ( $html as $item ) {
				?>
				ID: <?= $item['id'] ?><br>
				Date: <?= $item['date'] ?><br>
				Time: <?= $item['time'] ?><br>
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

	/**
	 * @var $messages \app\modules\mailparsing\controllers\DefaultController
	 */
	if ( isset( $message ) && $message == 3 ) { ?>
		<p>Посмотреть статистику <a href="<?= Url::to( [ 'default/view-stat' ] ) ?>">Go!</a></p>
	<?php } ?>


	<?php if ( isset( $ms ) ) { ?>

		<h4>Новые юзеры:<br></h4>

		<?php
		foreach ( $ms->messages['new-user'] as $val ) {
			echo $val . '<br>';
		}
		unset( $val ); ?>

		<hr>
		<h4>Новые проекты:<br></h4>

		<?php
		foreach ( $ms->messages['new-project'] as $val ) {
			echo $val . '<br>';
		}
		unset( $val );
		?>

		<hr>
		<h4>Количество постов:<br></h4>

		<?php echo count( $ms->messages['posts'] ) ?>

	<?php } ?>

</div>
