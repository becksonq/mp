<?php

/* @var $this yii\web\View */
ini_set( 'error_reporting', E_ALL );
ini_set( 'display_errors', 1 );
ini_set( 'display_startup_errors', 1 );

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

		/*print '<pre>';
		print_r( $msg_header );
		print '</pre>';*/

		$mails_data[$i]["time"] = time( $msg_header->MailDate );
		$mails_data[$i]["date"] = $msg_header->MailDate;

		foreach ( $msg_header->to as $data ) {
			// Кому
			$mails_data[$i]["to"] = $data->mailbox . "@" . $data->host;
		}

		foreach ( $msg_header->from as $data ) {
			// От кого
			$mails_data[$i]["from"] = $data->mailbox . "@" . $data->host;
		}

		if ( property_exists( $msg_header, 'subject' ) ) {
			$mails_data[$i]["title"] = get_imap_title( $msg_header->subject );
			//print $mails_data[$i]["title"] . "<br>";
		}

		// Тело письма
		$msg_structure = imap_fetchstructure( $connection, $i );

		// Вложенные файлы
		// Если есть вложенные файлы...
		if ( isset( $msg_structure->parts ) ) {

			$parts = $msg_structure->parts;

			// Количество вложенных файлов
			$count_parts = count( $parts );

//			for ( $h = 0; $h < $count_parts; $h++ ) {
//
//				if ( in_array( $msg_structure->parts[$h]->subtype, $mail_filetypes ) ) {
//
//					$count_subparts = count( $msg_structure->parts[$h] );
//
//					//print $count_subparts . "<br>";
//
//					for ( $g = 0; $g < $count_subparts; $g++ ) {
//
//						print '<pre>';
////						print $msg_structure->parts[$g]->encoding;
//						print $msg_structure->parts[$g]->subtype . "<br>";
//						print $msg_structure->parts[$g]->type . "<br>";
//
//						$mess = structure_encoding( $msg_structure->parts[$g]->encoding, imap_fetchbody( $connection, $i, $h, $g ) );
//
//						file_put_contents( "tmp/" . iconv( "utf-8", "cp1251", $msg_structure->parts[$g]->type . 'i' ), $mess );
//
//						print '</pre>';
//					}
//
//					//
//				}
//			}

			for ( $j = 1, $f = 2; $j < $count_parts; $j++, $f++ ) {

				if ( in_array( $parts[$j]->subtype, $mail_filetypes ) ) {

					foreach ( $parts[$j]->parts as $p ){
//						if ( in_array( $p->subtype, ['HTML'] ) ) {

//							print '<pre>';
//						print $p->parts[0]->encoding;
//							print_r( $p );
////						print( get_imap_title( $p->parameters[0]->value ) );
//							print '</pre>';
//						}

//						$file = structure_encoding(	$p->encoding, imap_fetchbody( $connection, $i, $f ));

//						file_put_contents( "tmp/" . iconv( "utf-8", "cp1251", $parts[$j]->bytes ),	$file );

						foreach ( $p as $pt ) {
							if( is_array( $pt ) ) {
								if( property_exists( $pt[0], 'encoding') ){
									print $pt[0]->parameters[0]->value;
								}
//								if ( in_array( $pt[0]->subtype, ['PLANE'] ) ) {
//									print '<pre>';
//									print_r( $pt[0] );
//									print '</pre>';
//								}
							}
						}

					}


					// RFC822
					$mails_data[$i]["attachs"][$j]["type"] = $parts[$j]->subtype; //print $mails_data[$i]["attachs"][$j]["type"] . "<br>";
					$mails_data[$i]["attachs"][$j]["size"] = $parts[$j]->bytes;

					$parameters = $parts[$j]->parameters;

//					print_r($parameters);
//					if (property_exists($parameters, 'attribute')) {
//
//						print "a" . "<br>";
//					}

//					print gettype( $msg_structure->parts[$j]->parameters->value ); exit;

//					if (property_exists($msg_structure->parts[$j]->parameters[0], 'value')) {
//
//						$y = get_imap_title( $msg_structure->parts[$j]->parameters[0]->value );
//						print $y;
//					}

//					$mails_data[$i]["attachs"][$j]["name"] = get_imap_title( $msg_structure->parts[$j]->parameters[0]->value );

//					$mails_data[$i]["attachs"][$j]["file"] = structure_encoding(
//							$msg_structure->parts[$j]->encoding, imap_fetchbody( $connection, $i, $f )
//					);

//					print '<pre>';
//					print $mails_data[$i]["attachs"][$j]["file"];
//					print '</pre>'; //exit;


					// Сохраняем файл в папку tmp/
//					file_put_contents( "tmp/" . iconv( "utf-8", "cp1251", $msg_structure->parts[$j]->bytes ),	$mails_data[$i]["attachs"][$j]["file"] );
//					file_put_contents( "tmp/" . iconv( "utf-8", "cp1251", $mails_data[$i]["attachs"][$j]["name"] ),	$mails_data[$i]["attachs"][$j]["file"] );
				}
			}
		}


	}
}

//print '<pre>';
//print_r( $mails_data );
//print '</pre>';

imap_close( $connection );

?>
