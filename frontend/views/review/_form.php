<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\Order;

/* @var $this yii\web\View */
/* @var $model frontend\models\Review */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="review-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'productID')->dropDownList(
        ArrayHelper::map(Order::find()->select(['productID'])->where('userID' == Yii::$app->user->getId())->all(),'productID','product.name'),
        ['prompt' => 'Select product']
    ) ?>

    <?= $form->field($model, 'review')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
