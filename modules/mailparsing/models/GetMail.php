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
    public $connection;
    public $message_num;
    public $message_header; // Заголовок письма
    public $msg_structure;
    public $parts;
    public $mails_data = [ ]; // Массив для вложений

    /*public function getMail(){


        $this->mm();

    }*/

    public function getMessages(){

        // Получаем количество писем
        $this->getNumMessage();

        for ( $i = 1; $i <= $this->message_num; $i++ ) {

            /*
             * Шапка письма
             * Можно использовать imap_header
             * */
            $this->message_header = imap_headerinfo( $this->connection, $i );

            $this->mails_data[$i]['id'] = $this->message_header->message_id;
            $this->mails_data[$i]["time"] = time( $this->message_header->MailDate );
            $this->mails_data[$i]["date"] = $this->message_header->MailDate;

            foreach ( $this->message_header->to as $data ) {
                // Кому
                $this->mails_data[$i]["to"] = $data->mailbox . "@" . $data->host; //print $mails_data[$i]["to"] . "<br>";
            }

            foreach ( $this->message_header->from as $data ) {
                // От кого
                $this->mails_data[$i]["from"] = $data->mailbox . "@" . $data->host; //print $mails_data[$i]["from"] . "<br>";
            }

            if ( property_exists( $this->message_header, 'subject' ) ) {
                $this->mails_data[$i]["title"] = $this->getImapTitle( $this->message_header->subject ); //print $mails_data[$i]["title"]     . "<br>";
            }

            // Тело письма
            $this->msg_structure = imap_fetchstructure( $this->connection, $i );

            //==========================================================================================================
            // Вложенные файлы
            // Если есть вложенные файлы...
            if ( isset( $this->msg_structure->parts ) ) {

                $this->parts = $this->msg_structure->parts;

                // Количество вложенных файлов
			    $count_parts = count( $this->parts ); //print $count_parts; exit;
//                $count_parts = 2;

                for ( $j = 1, $f = 2; $j<$count_parts; $j++, $f++ ) {

                    if ( in_array( $this->parts[ $j ]->subtype, $this->mail_filetypes ) ) {

                        $this->mails_data[$i]["attachs"][$j]["file"] = $this->structureEncoding(
                            $this->msg_structure->parts[$j]->encoding, imap_fetchbody($this->connection, $i, $f)
                        );

                        /*if ( property_exists( $this->parts[$j], 'encoding' ) ) {
//
                            $file_name = md5( time() ) . ".html";
                            $file = $this->structureEncoding( $this->parts[$j]->encoding, imap_fetchbody( $this->connection, $i, $f ) );

//                            $this->mails_data[$i]['part'] = $file;

						file_put_contents( "tmp/" . $file_name, $file );
                        }*/
                    }
                }
            }


        }

        $this->stopConnection();
        return $this->mails_data;
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
    public function getNumMessage()
    {
        // Стартуем imap
        $this->startConnection();
        $this->message_num = imap_num_msg( $this->connection );

        return $this->message_num;
    }

    /**
     * @return resource
     */
    public function startConnection()
    {
        $this->connection = imap_open( $this->mail_imap, $this->mail_login, $this->mail_password )
        or die( "can't connect: " . imap_last_error() );

        if ( !$this->connection ) {

            echo( "Ошибка соединения с почтой - " . $this->mail_login );
            exit;
        }
//        else {
            return $this->connection;
//        }
    }

    /**
     *
     */
    public function stopConnection()
    {
        imap_close( $this->connection );
        return;
    }

}