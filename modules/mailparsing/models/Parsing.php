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

class Parsing
{

    public $mail_array = [ ];
    public $el = [ ];


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

    public function getStrHtml( $object )
    {
        $mail_array = [ ];

        foreach ( $object as $key => $val ) {

            $mail_array[$key]['id'] = $val['id'];
            $mail_array[$key]['date'] = $val['date'];
            $mail_array[$key]['time'] = $val['time'];

            $file = $val['attachs'][1]['file'];

            $file = mb_strstr( $file, '<!' );

            $html = SHD::str_get_html( $file );
            $mail_array[$key]['html'] = $html->save();

        }
        unset( $val );

        $this->mail_array = $mail_array;
        return $this->mail_array;
    }


}