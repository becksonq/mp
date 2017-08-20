<?php

namespace app\modules\mailparsing\controllers;

use yii;
use yii\web\Controller;
use app\modules\mailparsing\models\GetMail;
use app\modules\mailparsing\models\Parsing;
use darkdrim\simplehtmldom\SimpleHTMLDom as SHD;

/**
 * Default controller for the `mailparsing` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render( 'index' );
    }

    /**
     * @return string
     */
    public function actionGetMail()
    {

        $mails = new GetMail();
        $messages = $mails->getMessages(); // Получаем массив писем

        if ( $messages ) {
            $session = Yii::$app->session;
            $session->open();
            $session->set( 'messages', $messages );
        }

        return $this->render( 'index', [
            'message' => 1,
        ] );

    }

    public function actionGetParts()
    {

        $html_array = [ ];

        $session = Yii::$app->session;
        $session->open();

        $parsing = new Parsing();
        $parsing->getStrHtml( $session['messages'] );


        foreach ( $parsing as $key => $value ) {
            foreach ( $value as $keys => $k ) {
                print $k['html'];
                $html_array[] = $k['html']; // Собираем html в массив
            }
        }
        unset( $value );
        unset( $parsing );

        if ( count( $html_array ) > 0 ) {
            $detales = new Parsing();
            $detales->getDetales( $html_array );
        }
//
        foreach ( $detales as $value ) {
            foreach ( $value as $val ) {
                foreach ( $val['user-name-link'] as $key => $v ) {
                    $users[] = $v; // Собираем юзеров в массив
//                    print $key . ':' . $v . '<br>';
                }
            }
            unset( $val );
        }
        unset( $value );

        $users = array_unique($users); echo count($users);
        foreach ($users as $val){
            print $val . '<br>';
        }

//        exit;

//        }

//        return $this->render( 'parsing', [
//            'parts' => $parts
//        ] );
    }

}
