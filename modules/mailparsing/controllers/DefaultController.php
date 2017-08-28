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
        $session = Yii::$app->session;
        $session->open();

        $parsing = new Parsing(); //print count($session['messages']);
        $parsing->getStrHtml( $session['messages'] );

        $html_array = $parsing->getHtmlArray( $parsing );
        unset( $parsing ); // Удаляем экземляр

        if ( count( $html_array ) > 0 ) {
            $detales = new Parsing();
            $detales->getDetales( $html_array );
        }

        // Получаем массив уникальных юзеров
        if ( isset( $detales ) ) {
            $users = $detales->getUsersArray( $detales );
            $u = $detales->getUsersFromTable( $users);
        }
        
        
        



        print '<pre>';
        print_r($users);
        print_r( $u );
        print '</pre>';


//        exit;

//        }

//        return $this->render( 'parsing', [
//            'parts' => $parts
//        ] );
    }

}
