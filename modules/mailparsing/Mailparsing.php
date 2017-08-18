<?php

namespace app\modules\mailparsing;

/**
 * Created by PhpStorm.
 * User: Администратор_
 * Date: 17.08.2017
 * Time: 19:57
 */
class Mailparsing extends \yii\base\Module
{

    public function init()
    {
        parent::init();

        $this->params['foo'] = 'bar';
        // ... остальной инициализирующий код ...
    }
}