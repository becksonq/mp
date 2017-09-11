<?php

namespace app\modules\mailparsing\controllers;

use yii;
use yii\web\Controller;
use app\modules\mailparsing\models\GetMail;
use app\modules\mailparsing\models\Parsing;

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

        $parsing = new Parsing();

        $html_array = $parsing->getHtmlArray( $session['messages'] );

        if ( count( $html_array ) > 0 ) {
            $session['htmls'] = $html_array;
        }
        // TODO: действие если массив пустой...

        return $this->render( 'index', [
            'message' => 2,
            'html'    => $html_array
        ] );
    }

    public function actionWriteDataToTable()
    {
        $session = Yii::$app->session;
        $session->open();

        $parsing = new Parsing();
        $parsing->whiteDataToTable( $session['htmls'] );

        $session['ms'] = $parsing;
        // TODO:
        return $this->render( 'index', [
            'message' => 3
        ] );
    }

    public function actionViewStat()
    {
        $session = Yii::$app->session;
        $session->open();
        return $this->render( 'index', [
            'ms' => $session['ms']
        ] );
    }

}
