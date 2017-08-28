<?php
/**
 * User: Администратор
 * Date: 27.08.2017
 * Time: 23:47
 */

namespace app\modules\mailparsing\models;


class H
{
    public static function h( $arg, $num = 1 )
    {
        if ( $num == 1 ) {
            print '<pre>';
            print_r( $arg );
            print '</pre>';
        }
        elseif ( $num == 0 ) {
            echo $arg . '<br>';
        }
        elseif ( $num == 2 ) {
            echo $num;
        }
    }
}