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

		//=============================================================================
		/**
		 * Новая реализация http://php.net/manual/ru/function.imap-fetchbody.php
		 */

		$struckture = imap_fetchstructure( $connection, $i );
		$message = imap_fetchbody( $connection, $i, 1 );
//		$name = $struckture->parts[1]->parameters[0]->value;
		$type = $struckture->parts[1]->type;
		if ( $type == 0 ) {
			$type = "text/";
		}
		elseif ( $type == 1 ) {
			$type = "multipart/";
		}
		elseif ( $type == 2 ) {
			$type = "message/";
		}
		elseif ( $type == 3 ) {
			$type = "application/";
		}
		elseif ( $type == 4 ) {
			$type = "audio/";
		}
		elseif ( $type == 5 ) {
			$type = "image/";
		}
		elseif ( $type == 6 ) {
			$type = "video";
		}
		elseif ( $type == 7 ) {
			$type = "other/";
		}
		$type .= $struckture->parts[1]->subtype;


		//=============================================================================

		//==
		$contentParts = count($msg_structure->parts); //print $contentParts; exit;

		//==


//		print '<pre>';
//		print_r( $msg_structure );
//		print '</pre>';
//		exit;

		$msg_body = imap_fetchbody( $connection, $i, 1 );

		//=============================================================================
//		$body = "";
//
//		$recursive_data = recursive_search( $msg_structure );
//
//		if ( $recursive_data["encoding"] == 0 || $recursive_data["encoding"] == 1 ) {
//
//			$body = $msg_body;
//		}
//
//		if ( $recursive_data["encoding"] == 4 ) {
//
//			$body = structure_encoding( $recursive_data["encoding"], $msg_body );
//		}
//
//		if ( $recursive_data["encoding"] == 3 ) {
//
//			$body = structure_encoding( $recursive_data["encoding"], $msg_body );
//		}
//
//		if ( $recursive_data["encoding"] == 2 ) {
//
//			$body = structure_encoding( $recursive_data["encoding"], $msg_body );
//		}
//
//		if ( !check_utf8( $recursive_data["charset"] ) ) {
//
//			$body = convert_to_utf8( $recursive_data["charset"], $msg_body );
//		}

		//print $body . "<br>";
//		$mails_data[$i]["body"] = base64_encode( $body );
		//=============================================================================

		// Вложенные файлы
		// Если есть вложенные файлы...
		if ( isset( $msg_structure->parts ) ) {

			// Количество вложенных файлов
			$count_parts = count( $msg_structure->parts );

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

//				print '<pre>';
//				print_r( $msg_structure[$j]->type );
////				print_r( $msg_structure->parts );
//				print '</pre>'; //exit;


//
//				if ( $msg_structure->parts[$j]->type == 0 ) {
//					foreach ( $msg_structure->parts[$j]->parts as $a ) {
						print '<pre>';
//						print 'a';
						print_r( $msg_structure->parts[$j]->subtype );
						print '</pre>'; //exit;
//					}
//				}


				if ( in_array( $msg_structure->parts[$j]->subtype, $mail_filetypes ) ) {


					for ( $t = 0; $t<count( $msg_structure->parts[$j] ); $t++ ) {
//						print '<pre>';
//						print $msg_structure->parts[$j]->subtype;
//						print '</pre>'; //exit;
					}



					// RFC822
					$mails_data[$i]["attachs"][$j]["type"] = $msg_structure->parts[$j]->subtype; //print $mails_data[$i]["attachs"][$j]["type"] . "<br>";
					$mails_data[$i]["attachs"][$j]["size"] = $msg_structure->parts[$j]->bytes;

					$parameters = $msg_structure->parts[$j]->parameters;

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

					$mails_data[$i]["attachs"][$j]["file"] = structure_encoding(
							$msg_structure->parts[$j]->encoding, imap_fetchbody( $connection, $i, $f )
					);

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
