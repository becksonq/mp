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
//        echo count( $object );
        $html_array = [ ];
        foreach ( $object as $key => $value ) {

//            print_r($value);

            /*foreach ( $value as $keys => $k ) {


                print $k['html'];
                $html_array[] = $k['html']; // Собираем html в массив
            }*/
        }
        unset( $value );

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

            $file = $val['attachs'][1]['file'];
//            $a[] = $file;
            H::h( $val['attachs'][1] );

            $file = mb_strstr( $file, '<!' );

            $html = SHD::str_get_html( $file );

            $mail_array[$key]['html'] = $html->save();

        }
        unset( $val );

//        H::h(count($a), 0);

        $this->mail_array = $mail_array;
        return $this->mail_array;
    }


}