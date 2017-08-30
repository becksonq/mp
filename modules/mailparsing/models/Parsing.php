<?php
/**
 * User: Администратор
 * Date: 20.08.2017
 * Time: 15:19
 */

namespace app\modules\mailparsing\models;

use Tests\Behat\Gherkin\ParserExceptionsTest;
use yii;
use darkdrim\simplehtmldom\SimpleHTMLDom as SHD;
use app\models\tables\Profile;

class Parsing
{

    public $mail_array = [ ];
    public $el = [ ];

    public function getUsersFromTable( $new_users )
    {
        $names = [ ];

        // Получаем данные из таблицы
        $u = Profile::find()
            ->asArray()
            ->where( [ 'not', [ 'firstname' => null ] ] )
            ->all();

        // Получаем имя/фамилию из данных
        foreach ( $u as $v ) {
            $names[] = $v['lastname'] . $v['firstname'];
        }

        // Удаляем пробелы из строки
        foreach ( $new_users as &$u ) {
            $u = str_replace( " ", "", $u );
        }

        // Сравниваем юзеров для нахождения новых
        $res = array_diff( $new_users, $names );

        return $res;
    }

    /**
     * Получаем массив страниц html
     * @param $object
     * @return array
     */
    public function getHtmlArray( $object )
    {
        // Получаем массив $session['messages']...
//        H::h( gettype( $object ), 0 ); // $object -> array

        $html_array = [ ];

        foreach ( $object as $key => $value ) {
            // Перебираем письма
            // $value --> письмо
//            H::h( $key, 0 );

//            H::h(count($value['attachs']), 0);

            foreach ( $value['attachs'] as $key1 => $file ) {

//                H::h( $file['file'] );

                $html = SHD::str_get_html( $file['file'] );

                foreach ( $html->find( 'table[bgcolor="#e8eaf6"]' ) as $key2 => $item ) {
//                    H::h($item); exit;
//                    $item['title']     = $article->find('div.title', 0)->plaintext;
                    foreach ( $item->find( 'td.project-name' ) as $project ) {
                        $files['project-name'] = $project;
                    }

                    foreach ( $item->find( 'a.user-name-link' ) as $uname ) {
                        $files['user-name'] = $uname;
                    }

                    foreach ( $item->find( 'td.post-text' ) as $text ) {
                        $files['user-name'] = $text;
                    }
//                    $files[] = $item;
                }

                foreach ( $files as $k => $v ) {

                    H::h( $v->plaintext, 0 );
                }


//                $html_array[] = $k['html']; // Собираем html в массив
            }
        }
        unset( $value );
        exit;
        return $html_array;
    }

    /**
     * Получаем массив уникальных юзеров
     * @param $object
     * @return array
     */
    public function getUsersArray( $object )
    {
        $users = [ ];

        foreach ( $object as $value ) {
            foreach ( $value as $val ) {
                foreach ( $val['user-name-link'] as $key => $v ) {
                    $users[] = $v; // Собираем юзеров в массив
//                    print $key . ':' . $v . '<br>';
                }
            }
            unset( $val );
        }
        unset( $value );

        $users = array_unique( $users );

        return $users;
    }

    /**
     * Получаем детали
     * @param $object
     * @return array
     */
    public function getDetales( $object )
    {
        $el = [ ];

        foreach ( $object as $key => $val ) {

            $html = SHD::str_get_html( $val );
//            $this->el[] = $html->find( 'table' );

            $el[$key]['project-name'] = $html->find( 'td.project-name' );
            $el[$key]['user-name-link'] = $html->find( 'a.user-name-link' );
            $el[$key]['post-text'] = $html->find( 'td.post-text' );

        }
        unset( $val );

        foreach ( $el as $keys => &$val ) {
            foreach ( $val['user-name-link'] as $key => &$v ) {
                $user = $v->innertext;
                $v = $user;
            }
        }
        unset( $val );

        $this->el = $el;
        return $this->el;
    }

    /**
     * Разбираем вложение
     * @param $object
     * @return array
     */
    public function getStrHtml( $object )
    {
//        print_r ($object); exit;
        $mail_array = [ ];

        foreach ( $object as $key => $val ) {
            // $val --> Письма

            $mail_array[$key]['id'] = $val['id'];
            $mail_array[$key]['date'] = $val['date'];
            $mail_array[$key]['time'] = $val['time'];

//            H::h( $key, 0 );

            foreach ( $val['attachs'] as $key1 => $part ) {
//                H::h( $part );

                //$file = mb_strstr( $part, '<!' );

                foreach ( $part as $key2 => $file ) {

//                    $file = mb_strstr( $file, 'quoted-printable' );
//                    $file = mb_strstr( $file, ': quoted-printable' );
//                    $file = str_replace( ': quoted-printable', '', $file );
                    $html = SHD::str_get_html( $file );

//                    H::h( $file, 0 );

                    $mail_array[$key][$key2]['html'] = $html;
//                    $mail_array[$key]['html'] = $html->save();

                }

            }
//
//            $file = $val['attachs'][1]['file'];


//
//
//            $mail_array[$key]['html'] = $html->save();

        }
        unset( $val );


//        exit;
//        $this->mail_array = $mail_array;
        return $mail_array;
    }


}