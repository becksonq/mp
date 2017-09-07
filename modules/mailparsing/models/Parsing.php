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
        $u = Profile::find()->asArray()->where( [
            'not',
            [ 'firstname' => null ]
        ] )->all();

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
     *
     * @param $object
     *
     * @return array
     */
    public function getHtmlArray( $object )
    {
        // Получаем массив $session['messages']...
        //        H::h( gettype( $object ), 0 ); // $object -> array

        $html_array = [ ];
        $files = [ ];

        foreach ( $object as $key => $value ) {
            // Перебираем письма
            // $value --> письмо
            foreach ( $value['attachs'] as $key1 => $file ) {

//                                H::h( $file['file'],0 );

                $html = SHD::str_get_html( $file['file'] ); //H::h($html,0); exit;

                foreach ( $html->find( 'style, meta, link, title, comment' ) as $style ) {
                    $style->outertext = '';
                }

                $str = $html->save();

                $html = SHD::str_get_html( $str );

//                H::h( $html, 0);

                if ( count( $html->find( 'table[bgcolor="#e8eaf6"]' ) ) ) {
                    foreach ( $html->find( 'table[bgcolor="#e8eaf6"]' ) as $item ) {


                        foreach ( $item->find( 'tr' ) as $key => $tr ) {

                            if ( !is_object( $tr ) ) {
                                continue;
                            }

                            if ( $t = $tr->find( 'td[class=project-name]' ) ) {
                                // Если нашли название проекта...
//                                H::h( 'pr = ' . $key, 1);
                                foreach ( $t as $k ) {

                                    $tmp_array['project'] = trim( $k->innertext );
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                                    $parent = $k->parent()->next_sibling(); // это tr
//                                    H::h($parent->outertext, 3);

//                                    if ( $n = $parent->find( 'table[align=center]' ) ) {

                                    foreach ( $parent->find( 'table[align=center]' ) as $el ) {
//                                            H::h(gettype($el), 3);
                                        $tmp_array['details']['user'] = trim( $el->find( 'a.user-name-link', 0 )->plaintext );
//
                                        $tmp_array['details']['post'] = trim( str_replace( '/\s{2,}/', ' ',
                                            $el->find( 'td.post-text', 0 )->innertext ) );
//
                                    }



                                    if ( $parent->next_sibling()->find( 'table[align=center]' ) ) {
                                        $parent = $parent->next_sibling();
//                                            H::h($parent->outertext, 3);

                                    }


//                                    }
                                    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                                }
                            }
//                            else {
//                                if ( $n = $tr->find( 'table[align=center]' ) ) {
//                                    // Если нашли детали...
//                                    H::h( 'dt = ' . $key, 1);
//                                    foreach ( $n as $el ) {
//                                        $tmp_array['project']['user'] = trim( $el->find( 'a.user-name-link',
//                                            0 )->plaintext );
//
//                                        $tmp_array['project']['post'] = trim( str_replace( '/\s{2,}/', ' ',
//                                            $el->find( 'td.post-text', 0 )->innertext ) );
//                                    }
//                                    unset ( $el );
//                                }
//
//                            }

                            if ( count( $tmp_array ) > 0 ) {
                                $files[] = $tmp_array;
                                $tmp_array = [ ];
                            }
                        }

                    }
                }
            }
        }

        H::h( $files );
//        $ar = [];
//        foreach ( $files as $key => $el ) {
//
////            H::h( $key, 0);
//            foreach ( $el as $a ) {
//                if ( !is_array( $a ) ) {
//                    $array['project'] = $a;
////                    H::h( $a, 1);
//                }
//                else {
//                    $array['details'] = $a;
//                }
//
//                $ar[] = $array;
//                $array = [];
//            }
//        }
//
//        H::h( $ar );


        exit;

        unset( $value );
        exit;
        return $html_array;
    }

    public function getParent( $el )
    {

        foreach ( $el->find( 'table[align=center]' ) as $el ) {
//                                            H::h(gettype($el), 3);
            $tmp_array['user'] = trim( $el->find( 'a.user-name-link', 0 )->plaintext );
//
            $tmp_array['post'] = trim( str_replace( '/\s{2,}/', ' ',
                $el->find( 'td.post-text', 0 )->innertext ) );
//
        }
        return $tmp_array;
    }

    /**
     * Получаем массив уникальных юзеров
     *
     * @param $object
     *
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
     *
     * @param $object
     *
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
     *
     * @param $object
     *
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