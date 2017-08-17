<?php

/* @var $this yii\web\View */
ini_set( 'error_reporting', E_ALL );
ini_set( 'display_errors', 1 );
ini_set( 'display_startup_errors', 1 );

set_time_limit(0);

$this->title = 'My Yii Application';
?>
<div class="site-index">

	<div class="jumbotron">
		<h1>Congratulations!</h1>

		<p class="lead">You have successfully created your Yii-powered application.</p>

		<p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
	</div>

	<div class="body-content">


	</div>
</div>
<?php

header( "Content-Type: text/html; charset=utf-8" );

//error_reporting( 0 );

require_once( "functions.php" );

$mail_login = "mailparsing@siberian.pro";
$mail_password = "LftimVjkjlt;m";
$mail_imap = "{imap.yandex.ru:993/imap/ssl}";

// Список учитываемых типов файлов
$mail_filetypes = [ "RFC822" ];

$connection = imap_open( $mail_imap, $mail_login, $mail_password ) or die( "can't connect: " . imap_last_error() );

if ( !$connection ) {

	echo( "Ошибка соединения с почтой - " . $mail_login );
	exit;
}
else {


	/*
	 * Количество писем в ящике
	 */
	$msg_num = imap_num_msg( $connection );

	$mails_data = [ ];

	for ( $i = 1; $i <= $msg_num; $i++ ) {

//		$e = imap_fetch_overview($connection, $i );

		// Шапка письма
		// imap_headerinfo
		$msg_header = imap_header( $connection, $i );

		print '<pre>';
		print_r( $msg_header );
		print '</pre>';

		$mails_data[$i]["time"] = time( $msg_header->MailDate );
		$mails_data[$i]["date"] = $msg_header->MailDate;

		foreach ( $msg_header->to as $data ) {
			// Кому
			$mails_data[$i]["to"] = $data->mailbox . "@" . $data->host; //print $mails_data[$i]["to"] . "<br>";
		}

		foreach ( $msg_header->from as $data ) {
			// От кого
			$mails_data[$i]["from"] = $data->mailbox . "@" . $data->host; //print $mails_data[$i]["from"] . "<br>";
		}

		if ( property_exists( $msg_header, 'subject' ) ) {
			$mails_data[$i]["title"] = get_imap_title( $msg_header->subject ); //print $mails_data[$i]["title"] . "<br>";
		}

		// Тело письма
		$msg_structure = imap_fetchstructure( $connection, $i );

		// Вложенные файлы
		// Если есть вложенные файлы...
		if ( isset( $msg_structure->parts ) ) {

			$parts = $msg_structure->parts;

			// Количество вложенных файлов
//			$count_parts = count( $parts );
			$count_parts = 5;

			for ( $j = 1, $f = 2; $j<$count_parts; $j++, $f++ ) {

				if ( in_array( $parts[ $j ]->subtype, $mail_filetypes ) ) {

					if ( property_exists( $parts[$j], 'encoding' ) ) {
//
						$file_name = md5( time() ) . ".html";
						$file = structure_encoding( $parts[$j]->encoding, imap_fetchbody( $connection, $i, $f ) );

						file_put_contents( "tmp/" . $file_name, $file );
					}

//					foreach ( $parts[ $j ]->parts as $p ) {
						/*if ( property_exists( $p, 'encoding' ) ) {
//						
							$file_name = md5( time() ) . ".html";
							$file = structure_encoding( $p->encoding, imap_fetchbody( $connection, $i, $f ) );

							file_put_contents( "tmp/" . $file_name, $file );
						}*/

						/*foreach ( $p as $key => $pt ) {
							if ( is_array( $pt ) ) {
								if ( property_exists( $pt[ 0 ], 'encoding' ) ) {
									
									$file_name = md5( time() ) . ".html";
//									print '<pre>';
//									print $pt[ 0 ]->parameters[ 0 ]->attribute;
//									print '</pre>';

									$file = structure_encoding( $pt[ 0 ]->encoding, imap_fetchbody( $connection, $i, $f ) );

									file_put_contents( "tmp/" . $file_name, $file );
//									file_put_contents( "tmp/" . iconv( "utf-8", "cp1251", $pt[ 0 ]->bytes ), $file );
								}
//
							}
						}*/

//					}


					// RFC822
//					$mails_data[$i]["attachs"][$j]["type"] = $parts[$j]->subtype; //print $mails_data[$i]["attachs"][$j]["type"] . "<br>";
//					$mails_data[$i]["attachs"][$j]["size"] = $parts[$j]->bytes;

//					$parameters = $parts[$j]->parameters;

//					if (property_exists($msg_structure->parts[$j]->parameters[0], 'value')) {
//
//						$y = get_imap_title( $msg_structure->parts[$j]->parameters[0]->value );
//						print $y;
//					}

//					$mails_data[$i]["attachs"][$j]["name"] = get_imap_title( $msg_structure->parts[$j]->parameters[0]->value );

//					$mails_data[$i]["attachs"][$j]["file"] = structure_encoding(
//							$msg_structure->parts[$j]->encoding, imap_fetchbody( $connection, $i, $f )
//					);


					// Сохраняем файл в папку tmp/
//					file_put_contents( "tmp/" . iconv( "utf-8", "cp1251", $msg_structure->parts[$j]->bytes ),	$mails_data[$i]["attachs"][$j]["file"] );
//					file_put_contents( "tmp/" . iconv( "utf-8", "cp1251", $mails_data[$i]["attachs"][$j]["name"] ),	$mails_data[$i]["attachs"][$j]["file"] );
				}
			}
		}
	}
}

imap_close( $connection );

?>
