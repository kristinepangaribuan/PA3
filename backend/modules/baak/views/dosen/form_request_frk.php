<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\baak\models\DosenJabatan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dosen-request-form">

    <?php $form = ActiveForm::begin(); ?>  
    
    <?= $form->field($model, 'pesan')->textarea()?>
    
    <?= Html::submitButton('Kirim', ['class' => 'btn btn-primary']) ?>
    
    <?php ActiveForm::end(); ?>
</div>
