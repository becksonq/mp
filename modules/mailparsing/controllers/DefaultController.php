<?php

namespace app\modules\mailparsing\controllers;

use yii\web\Controller;
use app\modules\mailparsing\models\GetMail;

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
        return $this->render('index');
    }

    public function actionGetMail(){

        $mails = new GetMail();
        $num = $mails->mm(); //print $num; exit;

        return $this->render('index', [
            'num' => $num,
        ]);

    }
}
