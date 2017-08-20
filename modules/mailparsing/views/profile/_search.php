<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\mailparsing\models\ProfileSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="profile-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'firstname') ?>

    <?= $form->field($model, 'lastname') ?>

    <?= $form->field($model, 'image') ?>

    <?= $form->field($model, 'slack_nickname') ?>

    <?php // echo $form->field($model, 'youtrack_nickname') ?>

    <?php // echo $form->field($model, 'delivery_digest_at_hour') ?>

    <?php // echo $form->field($model, 'delivery_digest_at_minutes') ?>

    <?php // echo $form->field($model, 'delivery_digest_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
