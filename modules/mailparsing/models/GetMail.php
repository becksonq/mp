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
//            if ( isset( $msg_structure->parts ) ) {
//
//                //======================================================================================================
//                for ( $j = 1, $f = 2; $j < count( $msg_structure->parts ); $j++, $f++ ) {
//
//                    if ( in_array( $msg_structure->parts[$j]->subtype, $this->mail_filetypes ) ) {
//
//                        $mails_data[$i]["attachs"][$j]["file"] = $this->structureEncoding(
//                            $msg_structure->parts[$j]->encoding, imap_fetchbody( $connection, $i, $f )
//                        );
////                        H::h( $j, 0 );
//
//                        /*if ( property_exists( $parts[$j], 'encoding' ) ) {
////
//                            $file_name = md5( time() ) . ".html";
//                            $file = $this->structureEncoding( $parts[$j]->encoding, imap_fetchbody( $connection, $i, $f ) );
//
////                            $mails_data[$i]['part'] = $file;
//
//						file_put_contents( "tmp/" . $file_name, $file );
//                        }*/
//                    }
//                }
//                //======================================================================================================
//            }


            if ( isset( $msg_structure->parts ) ) {


                for ( $j = 1, $f = 2; $j < count( $msg_structure->parts ); $j++, $f++ ) {

                    if ( in_array( $msg_structure->parts[$j]->subtype, $this->mail_filetypes ) ) {

                        $file = $this->structureEncoding( $msg_structure->parts[$j]->encoding, imap_fetchbody( $connection, $i, $f ) );

//                        H::h($file,0);
                        $mails_data[$i]["attachs"][$j]["file"] = $file;
//                        $mails_data[$i]["attachs"][$j]["file"] = $this->structureEncoding(
//                            $msg_structure->parts[$j]->encoding,
//                            imap_fetchbody( $connection, $i, $f )
//                        );

//                        file_put_contents("tmp/".iconv("utf-8", "cp1251", $mails_data[$i]["attachs"][$j]["name"]), $mails_data[$i]["attachs"][$j]["file"]);
                    }
                }
            }


        }

//        print_r($mails_data); exit;

        $this->stopConnection( $connection );
        return $mails_data;
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