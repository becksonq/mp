<?php
/**
 * User: Администратор
 * Date: 19.08.2017
 * Time: 17:31
 */

namespace app\modules\mailparsing\models;

use yii;


class GetMail
{

    public $mail_login = "mailparsing@siberian.pro";
    public $mail_password = "LftimVjkjlt;m";
    public $mail_imap = "{imap.yandex.ru:993/imap/ssl}";
    public $mail_filetypes = [ "RFC822" ]; // Список учитываемых типов файлов
//    public $connection;
//    public $message_num;
//    public $message_header; // Заголовок письма
//    public $msg_structure;
//    public $parts;
//    public $mails_data = [ ]; // Массив для вложений


    public function getMessages()
    {
        $mails_data = [ ];
        $connection = $this->startConnection();
        // Получаем количество писем
        $message_num = $this->getNumMessage( $connection );

        for ( $i = 1; $i <= $message_num; $i++ ) {

            /*
             * Шапка письма
             * Можно использовать imap_header
             * */
            $message_header = imap_headerinfo( $connection, $i );

            $mails_data[$i]['id'] = $message_header->message_id;
            $mails_data[$i]["time"] = time( $message_header->MailDate );
            $mails_data[$i]["date"] = $message_header->MailDate;

            foreach ( $message_header->to as $data ) {
                // Кому
                $mails_data[$i]["to"] = $data->mailbox . "@" . $data->host; //print $mails_data[$i]["to"] . "<br>";
            }

            foreach ( $message_header->from as $data ) {
                // От кого
                $mails_data[$i]["from"] = $data->mailbox . "@" . $data->host; //print $mails_data[$i]["from"] . "<br>";
            }

            if ( property_exists( $message_header, 'subject' ) ) {
                $mails_data[$i]["title"] = $this->getImapTitle( $message_header->subject ); //print $mails_data[$i]["title"]     . "<br>";
            }

            // Тело письма
            $msg_structure = imap_fetchstructure( $connection, $i );

            //==========================================================================================================
            // Вложенные файлы
            // Если есть вложенные файлы...
            if ( isset( $msg_structure->parts ) ) {

                $prefix = "";
                $part_array = [ ];

                if ( count( $msg_structure->parts ) > 0 ) {    // There some sub parts
                    foreach ( $msg_structure->parts as $count => $part ) {
                        $this->addPartToArray( $part, $prefix . ( $count + 1 ), $part_array );
                    }
                }

                foreach ( $part_array as $key => $value ) {

                    if ( stripos( $value['part_number'], '.' ) ) {
                        $file = trim( $this->structureEncoding( $value['part_object'],
                            imap_fetchbody( $connection, $i, $value['part_number'] ) ) );

                        $mails_data[$i]["attachs"][$key]["file"] = $file;
                    }
                }
            }
        }


        $this->stopConnection( $connection );
        return $mails_data;
    }

    public function addPartToArray( $obj, $partno, & $part_array )
    {
        $prefix = '';

        $part_array[] = [ 'part_number' => $partno, 'part_object' => $obj ];

//        H::h( $partno, 0);

        if ( $obj->type == 2 ) { // Check to see if the part is an attached email message, as in the RFC-822 type

//            H::h( $partno, 0 );

            if ( count( $obj->parts ) > 0 ) {    // Check to see if the email has parts
                foreach ( $obj->parts as $count => $part ) {

                    // Iterate here again to compensate for the broken way that imap_fetchbody() handles attachments
                    if ( count( $part->parts ) > 0 ) {
                        foreach ( $part->parts as $count2 => $part2 ) {
//                            H::h( $part2 );
//                            H::h(  "." . ( $count2 + 1 ), 0 );

                            if ( ( $count2 + 1 ) == 2 ) {
                                $this->addPartToArray( $part2, $partno . "." . ( $count2 + 1 ), $part_array );
                            }

                        }
                    }
//                    else {    // Attached email does not have a seperate mime attachment for text
//                        $part_array[] = array( 'part_number' => $partno . '.' . ( $count + 1 ), 'part_object' => $obj );
//                    }
                }
            }
//            else {    // Not sure if this is possible
//                $part_array[] = [ 'part_number' => $prefix . '.1', 'part_object' => $obj ];
//            }
        }
//        else {    // If there are more sub-parts, expand them out.
//            if ( count( $obj->parts ) > 0 ) {
//                foreach ( $obj->parts as $count => $p ) {
//                    $this->addPartToArray( $p, $partno . "." . ( $count + 1 ), $part_array );
//                }
//            }
//        }
    }


    private function structureEncoding( $encoding, $msg_body )
    {

        switch ( (int)$encoding ) {

            case 4:
                $body = imap_qprint( $msg_body );
                break;

            case 3:
                $body = imap_base64( $msg_body );
                break;

            case 2:
                $body = imap_binary( $msg_body );
                break;

            case 1:
                $body = imap_qprint( $msg_body );
//            $body = imap_8bit ( $msg_body );
                break;

            case 0:
                $body = $msg_body;
                break;

            default:
                $body = "";
                break;
        }

        return $body;
    }

    /**
     * @param $str
     * @return string
     */
    public function getImapTitle( $str )
    {

        $mime = imap_mime_header_decode( $str );

        $title = "";

        foreach ( $mime as $key => $m ) {

            if ( !$this->checkUTF8( $m->charset ) ) {

                $title .= convert_to_utf8( $m->charset, $m->text ); //print $title; exit;
            }
            else {

                $title .= $m->text;
            }
        }

        return $title;
    }

    /**
     * @param $charset
     * @return bool
     */
    private function checkUTF8( $charset )
    {

        if ( strtolower( $charset ) != "utf-8" ) {

            return false;
        }

        return true;
    }

    /**
     * Получаем количество писем
     * @return int
     */
    public function getNumMessage( $connection )
    {
        // Стартуем imap
        $this->startConnection();
        $message_num = imap_num_msg( $connection );

        return $message_num;
    }

    /**
     * @return resource
     */
    public function startConnection()
    {
        $connection = imap_open( $this->mail_imap, $this->mail_login, $this->mail_password )
        or die( "can't connect: " . imap_last_error() );

        if ( !$connection ) {

            echo( "Ошибка соединения с почтой - " . $this->mail_login );
            exit;
        }
//        else {
        return $connection;
//        }
    }

    /**
     *
     */
    public function stopConnection( $connection )
    {
        imap_close( $connection );
        return;
    }

}